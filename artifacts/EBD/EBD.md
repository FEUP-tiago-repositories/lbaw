# EBD: Database Specification Component

> ~~Project vision.~~

## A4: Conceptual Data Model

> ~~Brief presentation of the artifact goals.~~

### 1. Class diagram

<div align="center">
  <img src="./diagram.png" alt="Diagram">
  <p align="center">Figure 1: Class Diagram</p>
</div>

### 2. Additional Business Rules

| Identifier | Name                                | Description                                                                                                               |
| -----------|------------------------------------ | ------------------------------------------------------------------------------------------------------------------------- |
| BR10       | Deleted User Account                | Upon account deletion, reviews are kept but are made anonymous.                                                           |
| BR13       | Reservation schedule Constraint     | Customers cannot double-book in the same (or different) sport spaces at the same time slot.                               |
| BR16       | Business Owners Accounts Limitation | A business owner cannot make any reservation, review or mark a sports space as a favorite but can see all the sports spaces and other funcionalities.        |
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

> ~~Brief presentation of the artifact goals.~~

### A5.1. Relational Schema

> ~~Brief text about relational schema~~

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
| R19 | media(<ins>id</ins>, <ins>space_id</ins> -> space **NN**, media_url **NN**, is_cover **NN DF** False) |
| R20 | favorited(<ins>space_id</ins> -> space **NN**, <ins>customer_id</ins> -> customer **NN**, is_favorite **NN DF** False) |
| R21 | sport_type(<ins>id</ins>, name **UK NN**) |

<div align="center">
  <p>Table 2: Relational Schema Notations</p>
</div>

> Legend:
> - UK = UNIQUE KEY
> - NN = NOT NULL
> - DF = DEFAULT
> - CK = CHECK

#### Generalization Justification

| Class | Constraints | Style Used | Justification |
|-------|-------------|------------|----------------|
| Authenticated user | Disjoint, Complete | E/R style | Even though they're both users, Customers and Business owners have several different functions. Therefore, it is beneficial to use the E/R style for them, as it will make it easier to differentiate between customer and owner ids when doing tasks such as creating spaces, leaving reviews and responses or booking a space |
| Notification | Disjoint, Complete | E/R style | Depending on what they're notifying, notifications have different foreign keys. Therefore, the E/R style is the best to represent the notifications, as it provides a strong separation between each type of notification and we predict a lot of notifications will be made, so any unnecessary foreign keys should be present in classes |

<div align="center">
  <p>Table 3: Generalization Justifications</p>
</div>

### A5.2. Domains

> The specification of additional domains can also be made in a compact form, using the notation:

| Domain Name        | Domain Specification                                                |
|--------------------|---------------------------------------------------------------------|
| sportsType         | ENUM('Badminton', 'Basketball', 'Biking', 'Climbing', 'Football', 'Golf', 'Gym', 'Handball', 'Hockey', 'Martial Arts', 'Padel', 'Rugby', 'Running', 'Skating', 'Swimming', 'Tennis', 'Volleyball', 'Other') |

<div align="center">
  <p>Table 4: Domain Specification</p>
</div>

### A5.3. Schema validation

> To validate the Relational Schema obtained from the Conceptual Model, all functional dependencies are identified and the normalization of all relation schemas is accomplished. Should it be necessary, in case the scheme is not in the Boyce–Codd Normal Form (BCNF), the relational schema is refined using normalization.

| Table R01 | User |
|---|---|
| **Keys:** | {id}, {email}, {phone_no} |
| **Functional Dependencies:** |
| FD0101 | {id} -> {user_name, email, phone_no, role, is_deleted, is_banned, password, birthday, profile_pic_url} |
| FD0102 | {email} -> {id, user_name, phone_no, role, is_deleted, is_banned, password, birthday, profile_pic_url} |
| FD0103 | {phone_no} -> {id, user_name, email, role, is_deleted, is_banned, password, birthday, profile_pic_url} |
| **Normal Form** | BCNF |

| Table R02 | Business Owner |
|---|---|
| **Keys:** | {id}, {user_id} |
| **Functional Dependencies:** | None |
| **Normal Form** | BCNF |

| Table R03 | Customer |
|---|---|
| **Keys:** | {id}, {user_id} |
| **Functional Dependencies:** | None |
| **Normal Form** | BCNF |

| Table R04 | Space |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD0201 | {id} -> {title, address, description, space_pic_url, is_closed, sport_type, phone_no, email, #favorites, #reviews} |
| **Normal Form** | BCNF |

| Table R05 | Admin |
|---|---|
| **Keys:** | {id}, {email} |
| **Functional Dependencies:** |
| FD0301 | {id} -> {email, password} |
| FD0302 | {email} -> {id, password} |
| **Normal Form** | BCNF |

| Table R06 | Ban |
|---|---|
| **Keys:** | {id}, {user_id} |
| **Functional Dependencies:** |
| FD0401 | {id} -> {user_id -> user, admin_id -> admin, motive, time_stamp} |
| FD0402 | {user_id} -> {id, admin_id -> admin, motive, time_stamp} |
| **Normal Form** | BCNF |

| Table R07 | Review |
|---|---|
| **Keys:** | {id}, {booking_id} |
| **Functional Dependencies:** |
| FD0501 | {id} -> {user_id -> user, text, time_stamp, rating} |
| FD0502 | {booking_id} -> {id, user_id -> user, text, time_stamp, rating} |
| **Normal Form** | BCNF |

| Table R08 | Booking |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD0601 | {id} -> {space_id -> space, user_id -> user, booking_date, time_stamp, is_cancelled} |
| **Normal Form** | BCNF |

| Table R09 | Response |
|---|---|
| **Keys:** | {id}, {review_id} |
| **Functional Dependencies:** |
| FD0701 | {id} -> {user_id -> user, review_id -> review, text, time_stamp} |
| FD0702 | {review_id} -> {id, user_id -> user, review_id -> review, text, time_stamp} |
| **Normal Form** | BCNF |

| Table R10 | Payment |
|---|---|
| **Keys:** | {id}, {booking_id} |
| **Functional Dependencies:** |
| FD0801 | {id} -> {booking_id -> booking, payment_value, is_discounted, is_accepted, payment_provider_ref, time_stamp} |
| FD0802 | {booking_id} -> {id, payment_value, is_discounted, is_accepted, payment_provider_ref, time_stamp} |
| **Normal Form** | BCNF |

| Table R11 | Discount |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD0901 | {id} -> {space_id -> space, value, start_date, end_date} |
| **Normal Form** | BCNF |

| Table R12 | Notification |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD1001 | {id} -> {user_id -> user, time_stamp, is_read} |
| **Normal Form** | BCNF |

| Table R13 | Response Notification |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD1101 | {id} -> {user_id -> user, time_stamp, is_read, response_id -> response} |
| **Normal Form** | BCNF |

| Table R14 | Review Notification |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD1102 | {id} -> {user_id -> user, time_stamp, is_read, review_id -> review} |
| **Normal Form** | BCNF |

| Table R15 | Booking Confirmation Notification |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD1103 | {id} -> {user_id -> user, time_stamp, is_read, booking_id -> booking} |
| **Normal Form** | BCNF |

| Table R16 | Booking Reminder Notification |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD1104 | {id} -> {user_id -> user, time_stamp, is_read, booking_id -> booking} |
| **Normal Form** | BCNF |

| Table R17 | Booking Cancellation Notification |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD1105 | {id} -> {user_id -> user, time_stamp, is_read, booking_id -> booking} |
| **Normal Form** | BCNF |

| Table R18 | Schedule |
|---|---|
| **Keys:** | {booking_id} |
| **Functional Dependencies:** |
| FD1106 | {booking_id} -> {space_id -> space, start_time, duration, max_capacity} |
| **Normal Form** | BCNF |

| Table R19 | Media |
|---|---|
| **Keys:** | {media_id} |
| **Functional Dependencies:** |
| FD1201 | {media_id} -> {media_url, is_cover} |
| **Normal Form** | BCNF |

| Table R20 | Favorited |
|---|---|
| **Keys:** | {space_id, user_id} |
| **Functional Dependencies:** |
| FD1301 | {space_id, user_id} -> {is_favorite} |
| **Normal Form** | BCNF |

| Table R21 | Sport Type |
|---|---|
| **Keys:** | {id} |
| **Functional Dependencies:** |
| FD1401 | {id} -> {name} |
| **Normal Form** | BCNF |


> ~~If necessary, description of the changes necessary to convert the schema to BCNF.~~\
> ~~Justification of the BCNF.~~

---

## A6: Indexes, triggers, transactions and database population

> ~~Brief presentation of the artifact goals.~~

### 1. Database Workload

> A study of the predicted system load (database load). Estimate of tuples at each relation.

| Relation | Relation Name | Order of Magnitude | Estimated Growth |
|----------|---------------|-------------------|------------------|
| R01 | user | 10k | 100 / day |
| R02 | customer | 10k | 100 / day |
| R03 | business_owner | 1k | 10 / day |
| R04 | space | 1k | 10 / day |
| R05 | admin | 100 | 1 / day |
| R06 | ban | 100 | 1 / day |
| R07 | review | 10k | 100 / day |
| R08 | booking | 10k | 100 / day |
| R09 | response | 1k | 10 / day |
| R10 | payment | 10k | 100 / day |
| R11 | discount | 10k | 100 / day |
| R12 | notification | 100k | 1000 / day |
| R13 | response_notification | 10k | 100 / day |
| R14 | review_notification | 10k | 100 / day |
| R15 | booking_confirmation_notification | 10k | 100 / day |
| R16 | booking_cancelation_notification | 1k | 10 / day |
| R17 | booking_reminder_notification | 10k | 100 / day |
| R18 | schedule | 1k | 10 / day |
| R19 | media | 1k | 10 / day |
| R20 | favorited | 10k | 100 / day |
| R21 | sport_type | 100 | 1 / day |

<div align="center">
  <p>Table 5: Database Workload</p>
</div>

# Parei aqui

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