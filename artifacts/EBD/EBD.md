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
| R01 | User(<ins>userID</ins>, userID -> user **CK** user.role = business_owner, username **NN**, email **UK NN**, phoneNo **UK NN**, role NN, isDeleted **NN DF** False, isBanned **NN DF** False, password **NN**, birthDate **NN CK** birthDate > NOW - 18 years, profilePicURL) |
| R02 | Space(<ins>spaceID</ins>, title **NN**, address **NN**, description, spacePicURL, isClosed **NN DF** False, sportType, PhoneNo **UK NN**, email **UK NN**, \\favorites **NN**, \\reviews **NN**) |
| R03 | Admin(<ins>adminID</ins>, email **UK NN**, password **NN**) |
| R04 | Ban(<ins>banID</ins>, userID -> user, adminID -> admin, motive **NN**, timeStamp **NN DF** now) |
| R05 | Review(<ins>reviewID</ins>, userID -> user **CK** user.role = customer, text **NN**, timeStamp **NN DF** now, rating **NN CK** rating > 0 AND rating ≤ 5) |
| R06 | Booking(<ins>bookingID</ins>, spaceID ->space, userID ->user, bookingDate **NN**, timeStamp **NN DF** now, isCancelled **NN DF** False |
| R07 | Response(<ins>responseID</ins>, userID -> user **CK** user.role = business_owner , reviewID -> review, text **NN**, timeStamp **NN DF** now) |
| R08 | Payment(<ins>paymentID</ins>, paymentValue **NN CK** value > 0, isDiscounted **NN DF** False, isAccepted **NN DF** False, |
| R09 | Discount(<ins>discountID</ins>, spaceID -> space, value **NN CK** 0 < value < 100, startDate **NN**, endDate **NN CK** startDate < endDate) |
| R10 | Notification(<ins>notificationID</ins>, userID -> user, timeStamp **NN DF,** isRead **NN**) |
| R11 | Schedule( <ins>id_booking</ins> -> booking, spaceID -> space, startTime **NN**, duration **NN CK** duration > 0, maxCapacity **NN CK** maxCapacity > 0) |
| R12 | Media(<ins>mediaID</ins>, mediaURL **NN**, isCover **NN DF** False) |
| R13 | Favorited(<ins>spaceID</ins> -> space, <ins>userID</ins> -> user, isFavorite **NN**) |

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