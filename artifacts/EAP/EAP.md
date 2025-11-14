# EAP: Architecture Specification and Prototype

> Project vision.

## A7: Web Resources Specification

> Brief presentation of the artifact's goals.

### 1. Overview

| Module | Specification |
| ------ | ------ |
| **M01**: Home Page and Static Pages | Web resources associated with home page and static pages with static content. Pages as Home Page, About Us, FAQ, Terms of Service, Contact Us are associated with this module. |
| **M02**: Authentication and Users Profiles | Web resources associated with authentication and user profiles management. Pages as Sign-in and Sign-up Page, Logout, Edit Profile, User profile are associated with this module. |
| **M03**: Sports Space Library | Web resources associated with creating, editing or viewing sports spaces details, searching/filtering and recommended spaces catalog and favorited spaces. |
| **M04**: Bookings and Reviews | Web resources associated with bookings and reviews. Which include making, seeing and managing own reservations. |
| **M05**: Administration Pages | Web resources associated with administration pages, as admin dashboard and managing users, sports spaces and reviews. |

### 2. Permissions

| Acronym | Name | Description |
| ------ | ------ | ------ |
| **VIS** | Visitor | Users without authentication |
| **USR** | User | Authenticated users |
| **CST** | Customer | User who can book and review sports spaces |
| **BOW** | Business Owner | User who can have its own sports spaces |
| **ADM** | Admin | Platform administrator  |

### 3. OpenAPI Specification

> OpenAPI specification in YAML format to describe the vertical prototype's web resources.

> Link to the `a7_openapi.yaml` file in the group's repository.

```yaml
openapi: 3.0.0

...
```

---

## A8: Vertical prototype

> Brief presentation of the artifact goals.

### 1. Implemented Features

#### 1.1. Implemented User Stories

> Identify the user stories that were implemented in the prototype.

| User Story reference | Name | Priority | Responsible | Description |
|----------------------|------|----------|-------------|-------------|
| US01 | Name of the user story | Priority of the user story | Main responsible by the implementation | Description of the user story |

...

#### 1.2. Implemented Web Resources

> Identify the web resources that were implemented in the prototype.

> Module M01: Module Name

| Web Resource Reference | URL |
|------------------------|-----|
| R01: Web resource name | URL to access the web resource |

...

> Module M02: Module Name

...

### 2. Prototype

> Command to start the Docker image from the group's Container Registry. User credentials necessary to test all features. Link to the source code in the group's Git repository.

---

## Revision history

Changes made to the first submission:

1. Item 1
2. ..

---

GROUPYYgg, DD/MM/20YY

* Group member 1 name, email (Editor)
* Group member 2 name, email
* ...