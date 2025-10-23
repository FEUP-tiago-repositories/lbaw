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
| BR11       | Administrator Account               | Administrator accounts are independent of the user accounts, i.e. they cannot make reservations or create a sports space. |
| BR12       | Reservation details                 | A reservation must be associated with a user, sports space and schedule.                                                  |
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

> The Relational Schema includes the relation schemas, attributes, domains, primary keys, foreign keys and other integrity rules: UNIQUE, DEFAULT, NOT NULL, CHECK.\
> Relation schemas are specified in the compact notation:

| Relation reference | Relation Compact Notation |
|--------------------|---------------------------|
| R01 | Table1(<ins>id</ins>, attribute **NN**) |
| R02 | Table2(<ins>id</ins>, attribute → Table1 **NN**) |
| R03 | Table3(<ins>id</ins>, id2 → Table2, attribute **UK NN**) |
| R04 | Table4((<ins>id1</ins>, <ins>id2</ins>) → Table3, id3, attribute **CK** attribute \> 0) |

### 2. Domains

> The specification of additional domains can also be made in a compact form, using the notation:

| Domain Name | Domain Specification |
|-------------|----------------------|
| Today | DATE DEFAULT CURRENT_DATE |
| Priority | ENUM ('High', 'Medium', 'Low') |

### 3. Schema validation

> To validate the Relational Schema obtained from the Conceptual Model, all functional dependencies are identified and the normalization of all relation schemas is accomplished. Should it be necessary, in case the scheme is not in the Boyce–Codd Normal Form (BCNF), the relational schema is refined using normalization.

| **TABLE R01** | User |
|---------------|------|
| **Keys** | { id }, { email } |
| **Functional Dependencies:** |  |
| FD0101 | id → {email, name} |
| FD0102 | email → {id, name} |
| ... | ... |
| **NORMAL FORM** | BCNF |

> If necessary, description of the changes necessary to convert the schema to BCNF.\
> Justification of the BCNF.

---

## A6: Indexes, triggers, transactions and database population

> Brief presentation of the artifact goals.

### 1. Database Workload

> A study of the predicted system load (database load). Estimate of tuples at each relation.

| **Relation reference** | **Relation Name** | **Order of magnitude** | **Estimated growth** |
|------------------------|-------------------|------------------------|----------------------|
| R01 | Table1 | units | dozens |
| R02 | Table2 | units | dozens |
| R03 | Table3 | units | dozens |
| R04 | Table4 | units | dozens |

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