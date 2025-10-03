# ER: Requirements Specification Component

The _SportsHub_ system is being developed as a web-based platform that connects people who want flexible sports options with sports facilities and service providers. Unlike traditional long-term membership models, _SportsHub_ allows users to search, book and experience different sports activities anytime, anywhere, without commitments. It bridges the gap between users who want flexibility (such as digital nomads, casual players or people with irregular schedules) and businesses that want to optimize their underused spaces or promote their services in a wider market.

## A1: _SportsHub_

### <u> Goals, business context, environment and motivation </u>

The main goal of _SportsHub_ is to develop a web-based platform that simplifies access to sports activities, allowing people to easily discover, compare, and book sports experiences whenever and wherever they want to practice. The platform is positioned within the sports and fitness industry, competing with traditional booking systems and membership models by offering a unified marketplace for diverse sports activities with a strong focus on on-demand usage and user freedom.

_SportsHub_ addresses the challenges faced by both sides of the sports activity market. For users, current models create barriers such as monthly fees, lack of facility knowledge and difficulty with last-minute bookings. For businesses, the main challenge is underutilization of spaces during off-peak hours and struggles with visibility. _SportsHub_ creates a symbiotic relationship where users gain maximum flexibility and variety while businesses achieve better profitability and exposure.

### <u> Main Features </u>

The platform includes user registration and profile management, advanced search and filtering capabilities, detailed facility pages with reviews, an instant booking system with multiple payment options, comprehensive notification system, reservation management and a business dashboard for facility owners. The platform features adaptive and responsive design ensuring optimal usability across desktop, tablet, and mobile devices.

### <u> User Profiles </u>

_SportsHub_ serves three main user types: **Administrators** who manage the overall system and ensure smooth operation; **Individual Users** including casual sports players, tourists, and digital nomads who search for activities, book spaces, and write reviews; and **Businesses** (facility owners and providers) who register their spaces, manage availability and pricing, and interact with user feedback.

## A2: Actors and User stories

This artifact contains the specification of the actors and their user stories, serving as a basis for the system requirements

### A2.1. Actors

For _SportsHub_ website, the actors are represented in Figure 1 and described in Table 1.
<div align=center>
![Figure 1: SportsHub actors](./a2_diagram.png)
</div>

|    Actor    | Description |
|-------------|-------------|
| User | Generic user that can see public information such as sports facilities, prices, availability, reviews… |
| Visitor | Unauthenticated user that can register itself (sign-up) or sign-in in the system |
| Authenticated | Registered users can create, edit and delete their profiles |
| Customer | Authenticated users that can make, edit or cancel reservations and write reviews |
| Business Owner | Authenticated users that can register their spaces, manage availability and pricing and interact with user feedback |
| Administrator | Manages the overall systems and ensures a smooth experience |
| Payment Provider | Allows to make payments through external providers |
| OpenStreetMap API | External API responsible for the system's map service (OpenStreetMap) |
<p style="align-text:center;">Table 1: SportsHub actors description.</p>

### 2. User Stories

> User stories organized by actor.\
> For each actor, a table containing a line for each user story, and for each user story: an identifier, a name, a priority, and a description (following the recommended pattern). Below is a template for presenting the user stories.

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US01 | US Name 1 | High | Ana Alice | As an \<actor\>, I want to \<what\>, so that \<why\>. |
| US02 | US Name 2 | Medium | John Silva | As an \<actor\>, I want to \<what\>, so that \<why\>. |
| US03 | US Name 3 | Low | Francisco Alves | As an \<actor\>, I want to \<what\>, so that \<why\>. |

#### 2.1. Visitor

| Identifier | Name     | Priority | Responsible | Description                                                                                     |
|------------|----------|----------|-------------|-------------------------------------------------------------------------------------------------|
| US01       | Sign-in  | High     | k           | As a Visitor, I want to be able to authenticate into the system, so that I can access my profile and my bookings |
| US02       | Sign-up  | High     | k           | As a Visitor, I want to be able to create an account in the system, so that I can access its main features |

**Table 2: Visitor user stories**

#### 2.2. User

| Identifier | Name                      | Priority | Responsible     | Description                                                                 |
|------------|---------------------------|----------|-----------------|-----------------------------------------------------------------------------|
| US11       | See Home page             | High     | Tiago Oliveira  | As a User, I want to access the home page, so that I can see |
| US12       | See About page            | High     | Tiago Oliveira  | As a User, I want to access the about page, so that I can see a description about the main information of the website |
| US13       | See Services informations | High     | Tiago Oliveira  | As a User, I want to access the service's information, so I can understand how the website works. |
| US14       | Consult FAQ page          | High     | Tiago Oliveira  | As a User, I want to acess the FAQ, so I can get answers to common questions |
| US15       | Consult Contacts page     | High     | Tiago Oliveira  | As a User, I want to acess admin’s contacts, so I can come in touch with the website creators |
| US16       | Search Activities         | High     | Francisco Gomes | As a User, I want to search for activities, so that I can find those that spark my interest |
| US17       | Filter Activities         | High     | Francisco Gomes | As a User, I want to filter the results I get, so that I can more freely choose those that better suite me |
| US18       | See sports’ activity details | High   | Gustavo         | As a User, I want to see the details of the activity I select, so that I can plan what to choose |
| US19       | Viewing profiles          | High     | Gustavo         | As a User, I want to be able to view other’s profiles, so that I can access relevant information |

**Table 3: User user stories**

#### 2.3. Authenticated User

| Identifier | Name               | Priority | Responsible | Description                                                                 |
|------------|--------------------|----------|-------------|-----------------------------------------------------------------------------|
| US21       | Editing my profile | High     | k           | As an Authenticated User, I want to be able to edit my own profile, so that I can update my information |

**Table 4: Authenticated user user stories**

#### 2.4. Business Owner

| Identifier | Name                                  | Priority | Responsible | Description                                                                 |
|------------|---------------------------------------|----------|-------------|-----------------------------------------------------------------------------|
| US31       | Creating new activities               | High     | Joana       | As a Business Owner, I want to be able to create new activities, so that my clients can have access to new activities |
| US32       | Editing activities details            | High     | l           | As a Business Owner, I want to be able to edit the details of my activities, so that customers always have accurate and detailed information to help them decide and book |
| US33       | Setting activities schedules          | Medium   | l           | As a Business Owner, I want to set schedules for my activities, so that my customers know when activities are available |
| US34       | Marking activities as permanently closed | Medium | ç           | As a Business Owner, I want to mark activities as permanently closed, so that customers cannot see or try to book activities that are no longer available |
| US35       | Accepting or declining reservations   | Medium   | jn          | As a Business Owner, I want to be able to accept or decline customer reservations, so that |
| US36       | Seeing an activities’ reservations in a calendar | Medium | jj | As a Business Owner, I want to see all reservations for my activities in a calendar, so that I can easily track bookings and manage better schedules |
| US37       | Getting notified when a reservation is made | Low  | j           | As a Business Owner, I want to be notified whenever a customer makes a reservation, so that I can manage my schedule effectively |
| US38       | Discounts                             | Low      | j           | As a Business Owner, I want to create and manage discounts for my activities, so that I can attract more customers |
| US39       | Getting reservation reminders         | Low      | i           | As a Business Owner, I want my customers to receive automatic reminders of their reservations, so that the attendance rate improves and no-shows are reduced |

**Table 5: Business Owner user stories**

#### 2.5. Customer

| Identifier | Name                                      | Priority | Responsible | Description                                                                 |
|------------|-------------------------------------------|----------|-------------|-----------------------------------------------------------------------------|
| US41       | Book an activity                          | High     | j           | As a Customer, I want to be able to book an activity, so that I can secure a spot |
| US42       | Cancel my reservation                     | High     | ss          | As a Customer, I want to be able to cancel a reservation I made, so that I can free up the space if I am unable to attend |
| US43       | Review an activity                        | Medium   | ss          | As a Customer, I want to review an activity I have attended, so that other users can make informed decisions about the quality of each activity |
| US44       | Getting notifications of a reservation’s status | Low   | ss          | As a Customer, I want to receive a notification when a reservation is confirmed or cancelled so that I can always stay informed |
| US45       | Recommendations Algorithm                 | Low      | s           | As a Customer, I want to see spaces based on my preferences, so that I can have a more enjoyable and personalized experience |

**Table 6: Customer user stories**

#### 2.6. Admin

| Identifier | Name                          | Priority | Responsible | Description                                                                 |
|------------|-------------------------------|----------|-------------|-----------------------------------------------------------------------------|
| US51       | Manage user accounts          | Medium   | ds          | As an Admin, I want to manage user accounts, so that I can maintain control over access, ensure security, and keep the system organized |
| US52       | Deleting inappropriate comments | Medium  | a           | As an Admin, I want to delete inappropriate comments, so that the platform remains respectful and welcoming for all users |
| US53       | Deleting inappropriate activities | Medium | s           | As an Admin, I want to delete inappropriate activities, so that the platform remains safe and trustworthy for all users |

**Table 7: Admin user stories**

### 3. Supplementary Requirements

> Section including business rules, technical requirements, and restrictions.\
> For each subsection, a table containing identifiers, names, and descriptions for each requirement.

#### 3.1. Business rules

| Identifier | Name                      | Description                                                                 |
|------------|---------------------------|-----------------------------------------------------------------------------|
| BR01       | Unique Profile            | Only 1 profile per user                                                     |
| BR02       | Profile ownership         | A profile may only be edited by its owner or an administrator               |
| BR03       | Reservation details       | A reservation must include user, activity and schedule                      |
| BR04       | Reservation schedule restraint | Reservations can only be made on a valid schedule set by the owner    |
| BR05       | Unique reservation slot   | Customers cannot double-book the same time slot                             |
| BR06       | Reservation management    | Reservations can be accepted, declined, or cancelled by the Business Owner or the system |
| BR07       | Reservation ownership     | Only Business Owners can create, edit, schedule, or close activities        |

**Table 8: SportsHub Business Rules**

#### 3.2. Technical requirements

| Identifier | Name             | Description                                                                 |
|------------|------------------|-----------------------------------------------------------------------------|
| TR01       | Availability     | The system must be available 99% of the time in each 24h cycle              |
| TR02       | Compatibility    | The application must be compatible in different types of systems, such as computers, tablets and smartphones |
| TR03       | Development Tools | The system must be developed using HTML5, CSS, PHP and JavaScript, as well as compatible frameworks |
| TR04       | Usability        | The system must be easy and intuitive to use, as it is designed to be used by every age group without technical experience |
| TR05       | Security         | The system shall protect information from unauthorized access through the use of an authentication system. It should keep all sensitive information such as location and payment details encrypted |
| TR06       | Database         | The PostgreSQL database management system must be used, with a version of 11 or higher. |
| TR07       | Performance      | The system must support at least 60 concurrent reservations per minute     |

**Table 9: SportsHub Technical Requirements**

#### 3.3. Restrictions

| Identifier | Name                   | Description                                                                 |
|------------|------------------------|-----------------------------------------------------------------------------|
| C01        | Discount limit         | Discounts cannot exceed 100%                                                |
| C02        | Parental consent       | Minors must have parental approval to register                              |
| C03        | Realistic bookings     | Reservations can’t be made more than 1 year in advance                      |
| C04        | Geographical Limitations | Spaces used for activities must be within Portuguese territories           |
| C05        | Unique account         | There can be only 1 account per email and phone number                      |

**Table 10: SportsHub Project Restrictions**

---

## A3: Information Architecture

> Brief presentation of the artifact goals.

### 1. Sitemap

> Sitemap presenting the overall structure of the web application.\
> Each page must be identified in the sitemap.\
> Multiple instances of the same page (e.g. student profile in SIGARRA) are presented as page stacks.

### 2. Wireframes

> Wireframes for, at least, two main pages of the web application. Do not include trivial use cases (e.g. about page, contacts).

#### UIxx: Page Name

#### UIxx: Page Name

---

## Revision history

Changes made to the first submission:

1. Item 1
2. ...

---

GROUPYYgg, DD/MM/20YY

* Group member 1 name, email (Editor)
* Group member 2 name, email
* ...