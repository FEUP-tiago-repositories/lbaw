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

> Brief presentation of the artifact goals.

### 1. Actors

> Diagram identifying actors and their relationships.\
> Table identifying actors, including a brief description.

### 2. User Stories

> User stories organized by actor.\
> For each actor, a table containing a line for each user story, and for each user story: an identifier, a name, a priority, and a description (following the recommended pattern). Below is a template for presenting the user stories.

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US01 | US Name 1 | High | Ana Alice | As an \<actor\>, I want to \<what\>, so that \<why\>. |
| US02 | US Name 2 | Medium | John Silva | As an \<actor\>, I want to \<what\>, so that \<why\>. |
| US03 | US Name 3 | Low | Francisco Alves | As an \<actor\>, I want to \<what\>, so that \<why\>. |

#### 2.1. Visitor

| Identifier | Name     | Priority | Description                                                                                     |
|------------|----------|----------|-------------------------------------------------------------------------------------------------|
| US01       | Sign-in  | High     | As a Visitor, I want to be able to authenticate into the system, so that I can access my profile and my bookings |
| US02       | Sign-up  | High     | As a Visitor, I want to be able to create an account in the system, so that I can access its main features |
#### 2.2. User

#### 2.3. Authenticated User

| Identifier | Name               | Priority | Responsible | Description                                                                 |
|------------|--------------------|----------|-------------|-----------------------------------------------------------------------------|
| US21       | Editing my profile | High     | k           | As an Authenticated User, I want to be able to edit my own profile, so that I can update my information |

#### 2.4. Business Owner

| Identifier | Name                                  | Priority | Responsible | Description                                                                 |
|------------|---------------------------------------|----------|-------------|-----------------------------------------------------------------------------|
| US32       | Creating new activities               | High     | Joana       | As a Business Owner, I want to be able to create new activities, so that my clients can have access to new activities |
| US33       | Editing activities details            | High     | l           | As a Business Owner, I want to be able to edit the details of my activities, so that customers always have accurate and detailed information to help them decide and book |
| US34       | Setting activities schedules          | Medium   | l           | As a Business Owner, I want to set schedules for my activities, so that my customers know when activities are available |
| US35       | Marking activities as permanently closed | Medium | ç           | As a Business Owner, I want to mark activities as permanently closed, so that customers cannot see or try to book activities that are no longer available |
| US36       | Accepting or declining reservations   | Medium   | jn          | As a Business Owner, I want to be able to accept or decline customer reservations, so that |
| US37       | Seeing an activities’ reservations in a calendar | Medium | jj | As a Business Owner, I want to see all reservations for my activities in a calendar, so that I can easily track bookings and manage better schedules |
| US38       | Getting notified when a reservation is made | Low  | j           | As a Business Owner, I want to be notified whenever a customer makes a reservation, so that I can manage my schedule effectively |
| US39       | Discounts                             | Low      | j           | As a Business Owner, I want to create and manage discounts for my activities, so that I can attract more customers |
| US40       | Getting reservation reminders         | Low      | i           | As a Business Owner, I want my customers to receive automatic reminders of their reservations, so that the attendance rate improves and no-shows are reduced |

#### 2.5. Customer

| Identifier | Name                                      | Priority | Responsible | Description                                                                 |
|------------|-------------------------------------------|----------|-------------|-----------------------------------------------------------------------------|
| US41       | Book an activity                          | High     | j           | As a Customer, I want to be able to book an activity, so that I can secure a spot |
| US42       | Cancel my reservation                     | High     | ss          | As a Customer, I want to be able to cancel a reservation I made, so that I can free up the space if I am unable to attend |
| US43       | Review an activity                        | Medium   | ss          | As a Customer, I want to review an activity I have attended, so that other users can make informed decisions about the quality of each activity |
| US44       | Getting notifications of a reservation’s status | Low   | ss          | As a Customer, I want to receive a notification when a reservation is confirmed or cancelled so that I can always stay informed |
| US45       | Recommendations Algorithm                 | Low      | s           | As a Customer, I want to see spaces based on my preferences, so that I can have a more enjoyable and personalized experience |

#### 2.6. Admin

| Identifier | Name                          | Priority | Responsible | Description                                                                 |
|------------|-------------------------------|----------|-------------|-----------------------------------------------------------------------------|
| US51       | Manage user accounts          | Medium   | ds          | As an Admin, I want to manage user accounts, so that I can maintain control over access, ensure security, and keep the system organized |
| US52       | Deleting inappropriate comments | Medium  | a           | As an Admin, I want to delete inappropriate comments, so that the platform remains respectful and welcoming for all users |
| US53       | Deleting inappropriate activities | Medium | s           | As an Admin, I want to delete inappropriate activities, so that the platform remains safe and trustworthy for all users |

### 3. Supplementary Requirements

> Section including business rules, technical requirements, and restrictions.\
> For each subsection, a table containing identifiers, names, and descriptions for each requirement.

#### 3.1. Business rules

#### 3.2. Technical requirements

#### 3.3. Restrictions

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