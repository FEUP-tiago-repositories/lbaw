# EBD: Database Specification Component

> Project vision.

## A4: Conceptual Data Model

> Brief presentation of the artifact goals.

### 1. Class diagram

<div align="center">
  <img src="./uploads/diagram.png" alt="Diagram" width="80%">
  <p align="center">Figure 2: Class Diagram</p>
</div>

### 2. Additional Business Rules

| Identifier | Name                                | Description                                                                                                               |
| -----------|------------------------------------ | ------------------------------------------------------------------------------------------------------------------------- |
| BR10       | Deleted User Account                | Upon account deletion, reviews are kept but are made anonymous.                                                           |
| BR13       | Reservation schedule Constraint     | Customers cannot double-book in the same (or different) sport spaces at the same time slot.                               |
| BR16       | Business Owners Accounts Limitation | A business owner cannot make any reservation or review but can see all the sports spaces and other funcionalities.        |
| BR17       | Modify Reservations                 | Customers can cancel or modify reservations only before the reservation start time.                                       |
| BR18       | Deleted Business Account            | When a Business Owner deletes its profile, their sports spaces are deleted.                                               |
| BR19       | Closed Spaces                       | When a sports space is closed, all future reservations are canceled.                                                      |
| BR20       | Permanently Closed Spaces           | When a sports space is permanently closed, all future reservations are canceled and the space's data are deleted except the spaceId, name and main image. |
| BR21       | Past or canceled reservations for closed sport spaces | When a past or canceled reservation is about a closed space, the "Repeat Reservation" option is not displayed, it is not possible to provide a review of that reservation (if it is a past reservation) and it isn't possible to click on the space to access its page or view its details. |
| BR22       | Banned User                         | If a user is blocked by an administrator, they cannot do anything: in the case of a customer, they cannot make or edit reservations or leave reviews; in the case of a company, spaces are closed (not permanently) and new spaces cannot be created or edited, nor can they respond to reviews of spaces (which are now closed). |

<div align="center">
  <p>Table 1: Additional Business Rules</p>
</div>

## A5: Relational Schema, validation and schema refinement

> Brief presentation of the artifact goals.

### 1. Relational Schema

> Brief text about relational schema

| Relation reference | Relation Compact Notation |
|-------------------|---------------------------|
| R01 | user(<ins>id</ins>, username **NN**, email **UK NN**, phone_no **UK NN**, is_deleted **NN DF** False, is_banned **NN DF** False, password **NN**, birth_date **NN CK** birth_date > NOW - 18 years, profile_pic_url) |
| R02 | business_owner(<ins>id</ins>, user_id -> user **NN**) |
| R03 | customer(<ins>id</ins>, user_id -> user **NN**) |
| R04 | space(<ins>id</ins>, owner_id -> business_owner **NN**, sport_type_id -> sport_type **NN**, title **NN**, address **NN**, description **NN**, is_closed **NN DF** False, phone_no **NN**, email **NN**, num_favorites **NN**, num_reviews **NN**) |
| R05 | admin(<ins>id</ins>, email **UK NN**, password **NN**) |
| R06 | ban(<ins>id</ins>, user_id -> user **NN**, admin_id -> admin **NN**, motive **NN**, time_stamp **NN DF** now) |
| R07 | review(<ins>id</ins>, customer_id -> customer **NN**, booking_id -> booking **NN**, text **NN**, time_stamp **NN DF** now, environment_rating **NN CK** 1 ≤ environment_rating ≤ 5, equipment_rating **NN CK** 1 ≤ equipment_rating ≤ 5, service_rating **NN CK** 1 ≤ service_rating ≤ 5) |
| R08 | booking(<ins>id</ins>, space_id -> space **NN**, customer_id -> customer **NN**, schedule_id -> schedule **NN**, booking_created_at **NN DF** now, is_cancelled **NN DF** False) |
| R09 | response(<ins>id</ins>, owner_id -> business_owner **NN**, review_id -> review **NN**, text **NN**, time_stamp **NN DF** now) |
| R10 | payment(<ins>id</ins>, booking_id -> booking **NN**, payment_value **NN CK** value > 0, is_discounted **NN DF** False, is_accepted **NN DF** False, payment_provider_ref **NN**, time_stamp **NN DF** now) |
| R11 | discount(<ins>id</ins>, space_id -> space **NN**, percentage **NN CK** 0 < percentage < 100, start_date **NN**, end_date **NN CK** start_date < end_date) |
| R12 | notification(<ins>id</ins>, user_id -> user **NN**, time_stamp **NN DF** now, is_read **NN DF** False) |
| R13 | response_notification(<ins>id</ins>, notification_id -> notification **NN**, response_id -> response **NN**) |
| R14 | review_notification(<ins>id</ins>, notification_id -> notification **NN**, review_id -> review **NN**) |
| R15 | booking_confirmation_notification(<ins>id</ins>, notification_id -> notification **NN**, booking_id -> booking **NN**) |
| R16 | booking_cancelation_notification(<ins>id</ins>, notification_id -> notification **NN**, booking_id -> booking **NN**) |
| R17 | booking_reminder_notification(<ins>id</ins>, notification_id -> notification **NN**, booking_id -> booking **NN**) |
| R18 | schedule(<ins>id</ins>, space_id -> space **NN**, schedule_date **NN CK** schedule_date > now, start_time **NN CK** start_time > now, duration **NN CK** duration > 0, max_capacity **NN CK** max_capacity > 0) |
| R19 | media(<ins>id</ins>, space_id -> space **NN**, media_url **NN**, is_cover **NN DF** False) |
| R20 | favorited(<ins>space_id -> space</ins>, <ins>customer_id -> customer</ins>, is_favorite **NN DF** False) |
| R21 | sport_type(<ins>id</ins>, name **UK NN**) |

> Legend:
> - UK = UNIQUE KEY
> - NN = NOT NULL
> - DF = DEFAULT
> - CK = CHECK

### 2. Domains

> The specification of additional domains can also be made in a compact form, using the notation:

| Domain Name        | Domain Specification                                                |
|--------------------|---------------------------------------------------------------------|
| role               | ENUM('Business_Owner', 'Customer') |
| sportsType         | ENUM('Badminton', 'Basketball', 'Biking', 'Climbing', 'Football', 'Golf', 'Gym', 'Handball', 'Hockey', 'Martial Arts', 'Padel', 'Rugby', 'Running', 'Skating', 'Swimming', 'Tennis', 'Volleyball', 'Other') |
| notificationTypes  | ENUM('Confirmation', 'Cancelation', 'Reminder', 'Review') |

### 3. Schema validation

> To validate the Relational Schema obtained from the Conceptual Model, all functional dependencies are identified and the normalization of all relation schemas is accomplished. Should it be necessary, in case the scheme is not in the Boyce–Codd Normal Form (BCNF), the relational schema is refined using normalization.

| Table R01 | User |
|---|---|
| **Keys:** | {userID}, {email}, {phoneNo} |
| **Functional Dependencies:** |
| FD0101 | {userID} -> {username, email, phoneNo, role, isDeleted, isBanned, password, birthday, profilePicURL} |
| FD0102 | {email} -> {userID, username, phoneNo, role, isDeleted, isBanned, password, birthday, profilePicURL} |
| FD0103 | {phoneNo} -> {userID, username, email, role, isDeleted, isBanned, password, birthday, profilePicURL} |
| **Normal Form** | BCNF |

> check if we need user id key later

| Table R02 | Space |
|---|---|
| **Keys:** | {spaceID}, {email}, {phoneNo} |
| **Functional Dependencies:** | |
| FD0201 | {spaceID} -> (title, address, description, spacePicURL, isClosed, sportType, PhoneNo, email, \\favorites, \\reviews) |
| FD0202 | {email} -> (spaceID, title, address, description, spacePicURL, isClosed, sportType, PhoneNo, \\favorites, \\reviews) |
| FD0203 | {phoneNo} -> (spaceID, title, address, description, spacePicURL, isClosed, sportType, email, \\favorites, \\reviews) |
| **Normal Form** | BCNF |

| Table R03 | Admin |
|---|---|
| **Keys:** | {adminID}, {email} |
| **Functional Dependencies:** | |
| FD0301 | {adminID} -> (email, password) |
| FD0302 | {email} -> (adminID, password) |
| **Normal Form** | BCNF |

| Table R04 | Ban |
|---|---|
| **Keys:** | {banID}, {userID} |
| **Functional Dependencies:** | |
| FD0401 | {banID} -> {userID -> user, admin -> admin, motive, timeStamp} |
| FD0402 | {userID} -> {banID, adminID, motive, timeStamp} |
| **Normal Form** | BCNF |

| Table R05 | Review |
|---|---|
| **Keys:** | {reviewID} |
| **Functional Dependencies:** | |
| FD0501 | {reviewID} -> {userID -> user, text, timeStamp, rating} |
| **Normal Form** | BCNF |

| Table R06 | Booking |
|---|---|
| **Keys:** | {bookingID} |
| **Functional Dependencies:** | |
| FD0601 | {bookingID} -> {spaceID -> space, userID -> user, bookingDate, timeStamp, isCancelled} |
| **Normal Form** | BCNF |

| Table R07 | Response |
|---|---|
| **Keys:** | {responseID} |
| **Functional Dependencies:** | |
| FD0701 | {responseID} -> {userID -> user, reviewID -> review, text, timeStamp} |
| **Normal Form** | BCNF |

| Table R08 | Payment |
|---|---|
| **Keys:** | {paymentID} |
| **Functional Dependencies:** | |
| FD0801 | {paymentID} -> {paymentValue, isDiscounted, isAccepted} |
| **Normal Form** | BCNF |

| Table R09 | Discount |
|---|---|
| **Keys:** | {discountID} |
| **Functional Dependencies:** | |
| FD0901 | {discountID} -> {spaceID -> space, value, startDate, endDate} |
| **Normal Form** | BCNF |

| Table R10 | Notifications |
|---|---|
| **Keys:** | {notificationID} |
| **Functional Dependencies:** | |
| FD1001 | {notificationID} -> {userID -> user, timeStamp, isRead} |
| **Normal Form** | BCNF |

| Table R11 | Schedule |
|---|---|
| **Keys:** | {bookingID} |
| **Functional Dependencies:** | |
| FD1102 | {bookingID} -> {spaceID -> space, startTime, duration, maxCapacity} |
| **Normal Form** | BCNF |

| Table R12 | Media |
|---|---|
| **Keys:** | {mediaID} |
| **Functional Dependencies:** | |
| FD1201 | {mediaID} -> {mediaURL, isCover} |
| **Normal Form** | BCNF |

| Table R13 | Favorited |
|---|---|
| **Keys:** | {spaceID, userID} |
| **Functional Dependencies:** | *none* |
| **Normal Form** | BCNF |

> If necessary, description of the changes necessary to convert the schema to BCNF.\
> Justification of the BCNF.

---

## A6: Indexes, triggers, transactions and database population

> Brief presentation of the artifact goals.

### 1. Database Workload

> A study of the predicted system load (database load). Estimate of tuples at each relation.

| Relation | Relation Name | Order of Magnitude | Estimated Growth |
|----------|--------------|--------------------|------------------|
| R01      | User         | 10k                | 100 / hour       |
| R02      | Space        | 1k                 | 10 / hour        |
| R03      | Admin        | 100                | 1 / hour         |
| R04      | Ban          | 100                | 1 / hour         |
| R05      | Review       | 10k                | 100 / hour       |
| R06      | Booking      | 10k                | 100 / hour       |
| R07      | Response     | 1k                 | 10 / hour        |
| R08      | Payment      | 10k                | 100 / hour       |
| R09      | Discount     | 10k                | 100 / hour       |
| R10      | Notification | 100k               | 1000 / hour      |
| R11      | Schedule     | 1k                 | 10 / hour        |
| R12      | Media        | 1k                 | 10 / hour        |
| R13      | Favorited    | 10k                | 100 / hour       |

### 2. Proposed Indices

#### 2.1. Performance Indices

> Indices proposed to improve performance of the identified queries.

| **Index** | IDX01 |
|-----------|-------|
| **Relation** | Relation where the index is applied |
| **Attribute** | Attribute where the index is applied |
| **Type** | B-tree, Hash, GiST or GIN |
| **Cardinality** | Attribute cardinality: low/medium/high |
| **Clustering** | Clustering of the index |
| **Justification** | Justification for the proposed index |
| `SQL code` |  |

#### 2.2. Full-text Search Indices

> The system being developed must provide full-text search features supported by PostgreSQL. Thus, it is necessary to specify the fields where full-text search will be available and the associated setup, namely all necessary configurations, indexes definitions and other relevant details.

| **Index** | IDX01 |
|-----------|-------|
| **Relation** | Relation where the index is applied |
| **Attribute** | Attribute where the index is applied |
| **Type** | B-tree, Hash, GiST or GIN |
| **Clustering** | Clustering of the index |
| **Justification** | Justification for the proposed index |
| `SQL code` |  |

### 3. Triggers

> User-defined functions and trigger procedures that add control structures to the SQL language or perform complex computations, are identified and described to be trusted by the database server. Every kind of function (SQL functions, Stored procedures, Trigger procedures) can take base types, composite types, or combinations of these as arguments (parameters). In addition, every kind of function can return a base type or a composite type. Functions can also be defined to return sets of base or composite values.

| **Trigger** | TRIGGER01 |
|-------------|-----------|
| **Description** | Trigger description, including reference to the business rules involved |
| `SQL code` |  |

### 4. Transactions

> Transactions needed to assure the integrity of the data.

| SQL Reference | Transaction Name |
|---------------|------------------|
| Justification | Justification for the transaction. |
| Isolation level | Isolation level of the transaction. |
| `Complete SQL Code` |  |

## Annex A. SQL Code

> The database scripts are included in this annex to the EBD component.
>
> The database creation script and the population script should be presented as separate elements. The creation script includes the code necessary to build (and rebuild) the database. The population script includes an amount of tuples suitable for testing and with plausible values for the fields of the database.
>
> The complete code of each script must be included in the group's git repository and links added here.

### A.1. Database schema

> The complete database creation must be included here and also as a script in the repository.

### A.2. Database population

> Only a sample of the database population script may be included here, e.g. the first 10 lines. The full script must be available in the repository.

---

## Revision history

Changes made to the first submission:

(nothing)

### GROUP25122, 08/10/2025

- Group member 1 Gustavo Lourenço up202306578@up.pt
- Group member 2 Tiago Oliveira, up202007448@up.pt
- Group member 3 Tiago Yin, up202306438@up.pt
- Group member 4 Francisco Gomes, up20306498@up.pt