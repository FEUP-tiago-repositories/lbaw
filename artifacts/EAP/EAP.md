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

| User Story | Name | Priority | Description |
|------------|------|----------|-------------|
| US100 | Sign-in | High | As a Visitor, I want to be able to authenticate into the system, so that I can access my profile and my bookings |
| US101 | Sign-up | High | As a Visitor, I want to be able to create an account in the system, so that I can access its main features |
| US200 | See Home Page | High | As a User, I want to access the home page, so that I can have access to main features and a brief presentation of the website |
| US201 | See About page | High | As a User, I want to see the about page, so that I can see a description about the main information of the website |
| US202 | See Services informations | High | As a User, I want to see the service's information, so I can understand how the website works |
| US203 | Consult FAQ page | High | As a User, I want to see the FAQ, so I can get answers to common questions |
| US204 | Consult Contacts page | High | As a User, I want to see the admin's contacts, so I can come in touch with the website creators |
| US205 | See sports spaces details | High | As a User, I want to see the details of the sports space I select, so that I can plan what to choose |
| US206 | Full-text Search | High | As a user, I want to perform full-text searches, so that I can find relevant sports, spaces or businesses based on partial matches or broader search terms |
| US207 | Exact Match Search | High | As a user, I want to be able to perform an exact match search, so that I can quickly find specific sports, spaces or businesses based on precise keywords |
| US300 | View Profile | High | As an Authenticated User, I want to be able to view my own profile, so that I can always check my own information |
| US301 | Editing Profile | High | As an Authenticated User, I want to be able to edit my own profile, so that I can update my information and change my profile picture |
| US400 | Book a sport space | High | As a Customer, I want to be able to book a sport space, so that I can ensure a slot in that space |
| US401 | Edit reservation | High | As a Customer, I want to be able to edit my future reservations, so that I can keep my reservations up-to-date |
| US402 | Cancel reservation | High | As a Customer, I want to be able to cancel a reservation I made, so that I can free up the space if I am unable to attend |
| US403 | See Reservations | High | As a Customer, I want to be able to see my future or past reservations, so that I can remember and review past reservations and keep me updated or edit my future reservations |
| US404 | Review a sport space | High | As a Customer, I want to review a sport space I have attended, so that other users can make informed decisions about the quality of each space |
| US500 | Creating new spaces | High | As a Business Owner, I want to be able to create new sports spaces, so that my clients can interact with my sports space |
| US501 | Editing spaces details | High | As a Business Owner, I want to be able to edit the details of my spaces, so that customers always have accurate and detailed information to help them decide and book |
| US502 | Delete or hide spaces | High | As a Business Owner, I want to delete or hide sports spaces, so that customers cannot see or try to book spaces that are no longer available |
| US503 | Setting sports spaces schedules and capacity | High | As a Business Owner, I want to set schedules and capacities for my sports spaces, so that my customers know when each spaces are available |
| US504 | Accepting, declining, modifing or canceling reservations | High | As a Business Owner, I want to be able to accept, decline, modify or cancel customer reservations, so that I can manage my availability efficiently and avoid scheduling conflicts |
| US600 | Manage users accounts | High | As an Admin, I want to manage user accounts, so that I can maintain control over access and ensure security by searching, viewing, creating, deleting, blocking/unblocking and editing user accounts |
| US601 | Manage sports spaces | High | As an Admin, I want to delete inappropriate sports spaces, so that the platform remains safe and trustworthy for all users |
| US602 | Deleting inappropriate reviews | High | As an Admin, I want to delete inappropriate comments, so that the platform remains respectful and welcoming for all users |


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