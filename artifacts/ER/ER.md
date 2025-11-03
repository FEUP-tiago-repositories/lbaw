# ER: Requirements Specification Component

The _SportsHub_ system is being developed as a web-based platform that connects people who want flexible sports options with sports facilities and service providers. Unlike traditional long-term membership models, _SportsHub_ allows users to search, book and experience different sports activities anytime, anywhere, without commitments. It bridges the gap between users who want flexibility (such as digital nomads, casual players or people with irregular schedules) and businesses that want to optimize their underused spaces or promote their services in a wider market.

## A1: _SportsHub_

### <u> Goals, business context, environment and motivation </u>

The main goal of _SportsHub_ is to develop a web-based platform that simplifies access to sports activities, allowing people to easily discover, compare, and book sports experiences whenever and wherever they want to practice. The platform is positioned within the sports and fitness industry, competing with traditional booking systems and membership models by offering a unified marketplace for diverse sports activities with a strong focus on on-demand usage and user freedom.

_SportsHub_ addresses the challenges faced by both sides of the sports activity market. For users, current models create barriers such as monthly fees, lack of facility knowledge and difficulty with last-minute bookings. For businesses, the main challenge is underutilization of spaces during off-peak hours and struggles with visibility. _SportsHub_ creates a symbiotic relationship where users gain maximum flexibility and variety while businesses achieve better profitability and exposure.

### <u> Main Features </u>

The platform includes sign-in/sign-up, user registration and profile management, personalized search and filtering capabilities, detailed facility pages with reviews, an instant booking system with multiple payment options, comprehensive notification system, reservation management and a business dashboard for facility owners. The platform features adaptive and responsive design ensuring optimal usability across desktop, tablet, and mobile devices.

### <u> User Profiles </u>

_SportsHub_ serves four main user types: **Visitors** who are unauthenticated users that can search activities, browse the platform and may choose to sign in or sign up; **Customers** (including casual sports players, tourists, and digital nomads) who can search, book spaces and write reviews; **Businesses** (facility owners and providers) who register their spaces, manage availability and pricing, and interact with user feedback and **Administrators** who manage the overall system and ensure smooth operation;

## A2: Actors and User stories

This artifact contains the specification of the actors and their user stories, serving as a basis for the system requirements

### A2.1. Actors

For _SportsHub_ website, the actors are represented in Figure 1 and described in Table 1.

<div align="center">
  <img src="./a2_diagram.png" alt="Actors Diagram" width="70%">
  <p align="center">Figure 1: SportsHub actors</p>
</div>

| Actor             | Description                                                                                                         |
| ----------------- | ------------------------------------------------------------------------------------------------------------------- |
| User              | Generic user that can see public information such as sports facilities, prices, availability, reviews…              |
| Visitor           | Unauthenticated user that can register itself (sign-up) or sign-in in the system                                    |
| Authenticated     | Registered users can create, edit and delete their profiles                                                         |
| Customer          | Authenticated users that can make, edit or cancel reservations and write reviews                                    |
| Business Owner    | Authenticated users that can register their spaces, manage availability and pricing and interact with user feedback |
| Administrator     | Manages the overall systems and ensures a smooth experience                                                         |
| Payment Provider  | Allows to make payments through external providers                                                                  |
| OpenStreetMap API | External API responsible for the system's map service (OpenStreetMap)                                               |

<div align="center">
  <p>Table 1: SportsHub actors description.</p>
</div>

### A2.2. User Stories

For the _SportsHub_ website, consider the user stories that are presented in the following sections.

#### A2.2.1. Visitor

| Identifier  | Name             | Priority | Responsible | Description                                                                                                      |
| ------------| -----------------| -------- | ----------- | ---------------------------------------------------------------------------------------------------------------- |
| US100       | Sign-in          | High     | Gustavo     | As a Visitor, I want to be able to authenticate into the system, so that I can access my profile and my bookings |
| US101       | Sign-up          | High     | Gustavo     | As a Visitor, I want to be able to create an account in the system, so that I can access its main features       |
| US102       | Recover Password | Medium   | Gustavo     | As a Visitor, I want to be able to recover my password, so that I can get it back in case I forget it.               |

<div align="center">
  <p>Table 2: Visitor user stories.</p>
</div>

#### A2.2.2. User

| Identifier | Name                            | Priority | Responsible    | Description                                                                                                                    |
| -----------| ------------------------------- | -------- | ---------------| ------------------------------------------------------------------------------------------------------------------------------ |
| US200      | See Home page                   | High     | Tiago Oliveira | As a User, I want to access the home page, so that I can have access to main features and a brief presentation of the website. |
| US201      | See About page                  | High     | Tiago Oliveira | As a User, I want to see the about page, so that I can see a description about the main information of the website.            |
| US202      | See Services informations       | High     | Tiago Oliveira | As a User, I want to see the service's information, so I can understand how the website works.                                 |
| US203      | Consult FAQ page                | High     | Tiago Oliveira | As a User, I want to see the FAQ, so I can get answers to common questions.                                                    |
| US204      | Consult Contacts page           | High     | Tiago Oliveira | As a User, I want to see the admin’s contacts, so I can come in touch with the website creators.                                   |
| US205      | See sports spaces details       | High     | Gustavo        | As a User, I want to see the details of the sports space I select, so that I can plan what to choose.                          |
| US206      | Full-text Search                | High     | Francisco      | As a user, I want to perform full-text searches, so that I can find relevant sports, spaces or businesses based on partial matches or broader search terms. |
| US207      | Exact Match Search              | High     | Francisco      | As a user, I want to be able to perform an exact match search, so that I can quickly find specific sports, spaces or businesses based on precise keywords. |
| US208      | Search Filters                  | Medium   | Francisco      | As a user, I want to apply filters (e.g., date range, sport type, location, business) to my search results, so that I can more freely choose those that better suit me.|
| US209      | Search over multiple attributes | Medium   | Francisco      | As a user, I want to search across multiple attributes(e.g., sport type, location, business), so that I can find relevant results based on various criteria. |
| US210      | Placeholders in Form Inputs     | Medium   | Tiago Oliveira | As a user, I want to see placeholders in form input fields, so that I can easily understand what information is expected in each field before I start typing. |
| US211      | Contextual Error Messages       | Medium   | Tiago Oliveira | As a user, I want to receive contextual error messages when I enter invalid information in form fields, so that I can quickly understand what went wrong and how to fix it.|
| US212      | Contextual Help                 | Medium   | Tiago Oliveira | As a user, I want to access contextual help for various features in the system, so that I can better understand how to use the platform and its functionalities. |

<div align="center">
  <p>Table 3: User user stories.</p>
</div>

#### A2.2.3. Authenticated User

| Identifier  | Name                 | Priority | Responsible | Description                                                                                                                            |
| ------------| ---------------------| -------- | ------------| -------------------------------------------------------------------------------------------------------------------------------------- |
| US300       | View my profile      | High     | Francisco   | As an Authenticated User, I want to be able to view my own profile, so that I can always check my own information.                     |
| US301       | Editing my profile   | High     | Francisco   | As an Authenticated User, I want to be able to edit my own profile, so that I can update my information and change my profile picture. |
| US302       | Delete my profile    | Medium   | Francisco   | As an Authenticated User, I want to be able to delete my own profile, so that I can erase my account if I no longer wish to use it.   |
| US303       | Manage notifications | Medium   | Tiago Yin   | As an Authenticated User, I want to be able to see my own notifications, so I can read all, clean or mark as read.                     |

<div align="center">
  <p>Table 4: Authenticated user user stories.</p>
</div>

#### A2.2.4. Customer

| Identifier  | Name                                   | Priority | Responsible | Description                                                                                                                                                                     |
| ------------|--------------------------------------- | -------- | ----------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| US400       | Book a sport space                     | High     | Tiago Oliveira | As a Customer, I want to be able to book a sport space, so that I can ensure a slot in that space.                                                                              |
| US401       | Edit my future reservation             | High     | Tiago Oliveira | As a Customer, I want to be able to edit my future reservations, so that I can keep my reservations up-to-date.                                                                 |
| US402       | Cancel my future reservation           | High     | Tiago Oliveira | As a Customer, I want to be able to cancel a reservation I made, so that I can free up the space if I am unable to attend.                                                      |
| US403       | See my reservations                    | High     | Tiago Oliveira | As a Customer, I want to be able to see my future or past reservations, so that I can remember and review past reservations and keep me updated or edit my future reservations. |
| US404       | Review a sport space                   | High     | Tiago Yin   | As a Customer, I want to review a sport space I have attended, so that other users can make informed decisions about the quality of each space.                                 |
| US405       | Getting notifications of a reservation | Low      | Tiago Yin   | As a Customer, I want to receive a notification when a reservation is confirmed, cancelled or is very soon so that I can always stay informed.                                  |
| US406       | Recommendations Algorithm              | Low      | Francisco   | As a Customer, I want to see spaces based on my preferences, so that I can have a more enjoyable and personalized experience.                                                   |
| US407       | Favorites                              | Low      | Francisco   | As a Customer, I want to choose my favorite spaces and see them on my profile, so that I can remember which spaces I enjoyed the most and want to return to.                    | 

<div align="center">
  <p>Table 5: Customer user stories.</p>
</div>

#### A2.2.5. Business Owner

| Identifier  | Name                                                     | Priority | Responsible | Description                                                                                                                                                                         |
| ------------|--------------------------------------------------------- | -------- | ----------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| US500       | Creating new sports spaces                               | High     | Gustavo     | As a Business Owner, I want to be able to create new sports spaces, so that my clients can interact with my sports space.                                                           |
| US501       | Editing sports spaces details                            | High     | Gustavo     | As a Business Owner, I want to be able to edit the details of my spaces, so that customers always have accurate and detailed information to help them decide and book.              |
| US502       | Delete or hide sports spaces                             | High     | Gustavo     | As a Business Owner, I want to delete or hide sports spaces, so that customers cannot see or try to book spaces that are no longer available.                                       |
| US503       | Setting sports spaces schedules and capacity             | High     | Gustavo     | As a Business Owner, I want to set schedules and capacities for my sports spaces, so that my customers know when each spaces are available.                                         |
| US504       | Accepting, declining, modifing or canceling reservations | High     | Tiago Yin   | As a Business Owner, I want to be able to accept, decline, modify or cancel customer reservations, so that I can manage my availability efficiently and avoid scheduling conflicts. |
| US505       | Seeing space's reservations in a calendar                | Medium   | Tiago Yin   | As a Business Owner, I want to see all reservations for my spaces in a calendar, so that I can easily track bookings and manage better schedules.                                   |
| US506       | Respond to reviews                                       | Low      | Gustavo     | As a Business Owner, I want respond to reviews in my sports spaces, so that I can appreciate for positive comments and provide a response to negative reviews.                      |
| US507       | Getting notified when a reservation is made              | Low      | Tiago Yin   | As a Business Owner, I want to be notified whenever a customer makes a reservation, so that I can manage my schedule effectively                                                    |
| US508       | Discounts                                                | Low      | Francisco   | As a Business Owner, I want to create and manage discounts for my sports spaces, so that I can attract more customers                                                               |

<div align="center">
  <p>Table 6: Business Owner user stories.</p>
</div>

#### A2.2.6. Admin

| Identifier  | Name                           | Priority | Responsible | Description                                                                                                                                                                                           |
| ------------|------------------------------- | -------- | ----------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| US600       | Manage users accounts          | High     | Francisco   | As an Admin, I want to manage user accounts, so that I can maintain control over access and ensure security by searching, viewing, creating, deleting, blocking/unblocking and editing user accounts. |
| US601       | Manage sports spaces           | High     | Francisco   | As an Admin, I want to delete inappropriate sports spaces, so that the platform remains safe and trustworthy for all users.                                                                           |
| US602       | Deleting inappropriate reviews | High     | Tiago Yin   | As an Admin, I want to delete inappropriate comments, so that the platform remains respectful and welcoming for all users.                                                                            |

<div align="center">
  <p>Table 7: Admin user stories.</p>
</div>

### A2.3. Supplementary Requirements

This section contains business rules, technical requirements and other restrictions on the SportsHub's project.

#### A2.3.1. Business rules

| Identifier | Name                                | Description                                                                                                               |
| -----------|------------------------------------ | ------------------------------------------------------------------------------------------------------------------------- |
| BR10       | Deleted User Account                | Upon account deletion, reviews are kept but are made anonymous.                                                           |
| BR11       | Administrator Account               | Administrator accounts are independent of the user accounts, i.e. they cannot make reservations or create a sports space. |
| BR12       | Reservation details                 | A reservation must be associated with a user, sports space and schedule.                                                  |
| BR13       | Reservation schedule Constraint     | Customers cannot double-book in the same (or different) sport spaces at the same time slot.                               |
| BR14       | Valid Reviews                       | Customers can only leave a review at sports spaces where they have already been to (booked in the past)                   |
| BR15       | Reservation Dates and hours         | Customers cannot book on a past date or past time of the same day.                                                        |
| BR16       | Business Owners Accounts Limitation | A business owner cannot make any reservation, review or mark a sports space as a favorite but can see all the sports spaces and other funcionalities.        |
| BR17       | Modify Reservations                 | Customers can cancel or modify reservations only before the reservation start time.                                       |
| BR18       | Deleted Business Account            | When a Business Owner deletes its profile, their sports spaces are deleted.                                               |
| BR19       | Closed Spaces                       | When a sports space is closed, all future reservations are canceled.                                                      |
| BR20       | Permanently Closed Spaces           | When a sports space is permanently closed, all future reservations are canceled and the space's data are deleted except the spaceId, name and main image. |
| BR21       | Past or canceled reservations for closed sport spaces | When a past or canceled reservation is about a closed space, the "Repeat Reservation" option is not displayed, it is not possible to provide a review of that reservation (if it is a past reservation) and it isn't possible to click on the space to access its page or view its details. |
| BR22       | Banned User                         | If a user is blocked by an administrator, they cannot do anything: in the case of a customer, they cannot make or edit reservations or leave reviews; in the case of a company, spaces are closed (not permanently) and new spaces cannot be created or edited, nor can they respond to reviews of spaces (which are now closed). |

<div align="center">
  <p>Table 8: SportsHub Business Rules</p>
</div>

#### A2.3.2. Technical requirements

| Identifier  | Name                   | Description                                                                                                                                                                                               |
| ----------- | ---------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| TR10        | Availability           | The system must be available 99% of the time in each 7 days cycle.                                                                                                                                        |
| <b>TR11</b> | <b> Compatibility </b> | <b> The application must be compatible in different types of systems, such as computers, tablets and smartphones </b>                                                                                     |
| TR12        | Development Tools      | The system must be developed using HTML5, CSS, PHP and JavaScript, as well as compatible frameworks                                                                                                       |
| <b>TR13</b> | <b>Usability</b>       | <b>The system must be easy and intuitive to use, as it is designed to be used by every age group without technical experience</b>                                                                         |
| <b>TR14</b> | <b>Security</b>        | <b>The system shall protect information from unauthorized access through the use of an authentication system. It should keep all sensitive information such as location and payment details encrypted</b> |
| TR15        | Database               | The PostgreSQL database management system must be used, with a version of 11 or higher.                                                                                                                   |
| TR16        | Performance            | The system must support at least 60 concurrent reservations per minute.                                                                                                                                   |

<div align="center">
  <p>Table 9: SportsHub Technical Requirements</p>
</div>

#### A2.3.3. Restrictions

| Identifier | Name               | Description                                            |
| ---------- | ------------------ | ------------------------------------------------------ |
| R10        | Discount limit     | Discounts cannot exceed 100%.                          |
| R11        | Age restrictions   | Minors cannot create an account on the website.        |
| R12        | Realistic bookings | Reservations can’t be made more than 1 year in advance |
| R13        | Unique account     | There can be only 1 account per email and phone number |

<div align="center">
  <p>Table 10: SportsHub Project Restrictions</p>
</div>

## A3: Information Architecture

The goal of this artifact is to give a brief overview of the information architecture. This is important for us to identify user requirements, as well as to preview and plan the application organization and user interface of the final product. 

This artifact contains a sitemap with the main detailing the overall structure of the pages and six wireframes with the functionality and content of the more important pages.

### A3.1. Sitemap

The image below represents the pages that are going to exist in SportsHub and how they can be accessed or used. It is a simple design of the process of using the website.

<div align="center">
  <img src="./sitemap.png" alt="Sitemap" width="80%">
  <p align="center">Figure 2: SportsHub sitemap</p>
</div>

> Note: there is no specific page for tags. They will be presented as part of the search pages, sport spaces and other related pages.

### A3.2. Wireframes

The wireframes below show the template and localization of the important interactive elements. The home page is the most significant page in our system and because of it it is represented below, the next pages that required some clarification and thinking about its usability are the search page, profiles pages (customer profile / business owner profile) and the sports space page. As such they also have wireframes.

#### UI01: Home Page

<div align="center">
  <img src="./wireframes/homepage_wireframe.png" alt="Home page wireframe" width="80%">
  <p align="center">Figure 3: Home page wireframe</p>
</div>

#### UI51: Search Page

<div align="center">
  <img src="./wireframes/activities_catalog_wireframe.png" alt="Search page wireframe" width="80%">
  <p align="center">Figure 4: Search page wireframe</p>
</div>

#### UI52: Sports Space Details Page

<div align="center">
  <img src="./wireframes/space_wireframe1.png" alt="Sports space details page wireframe" width="80%">
  <img src="./wireframes/space_wireframe2.png" alt="Sports space details page wireframe" width="80%">
  <p align="center">Figure 7: Sports space details page wireframe</p>
</div>

#### UI10: Profiles Pages

<div align="center">
  <img src="./wireframes/customer_profile_wireframe.png" alt="Customer profile page wireframe" width="80%">
  <p align="center">Figure 5: Customer profile page wireframe</p>
  <img src="./wireframes/business_owner_profile_wireframe.png" alt="Business owner profile page wireframe" width="80%">
  <p align="center">Figure 6: Business owner profile page wireframe</p>
</div>

## Revision history

Changes made to the first submission:

1. Change user story identifiers to 3 digits. Add new user stories (US102, US207, US209, US210, US211, US212, US301, US302 and US303) and minimal changes to others (US100, US206, US208, US300 and US600). (12/10/2025, by Tiago Oliveira)
2. Add new business rules (BR18 to BR22). (23/10/2025, by Tiago Oliveira)´
3. Fix BR16 (especify restriction with favorited spaces). (03/11/2025, by Tiago Oliveira)

###GROUP25122, 08/10/2025

- Group member 1 Gustavo Lourenço up202306578@up.pt
- Group member 2 Tiago Oliveira, up202007448@up.pt
- Group member 3 Tiago Yin, up202306438@up.pt
- Group member 4 Francisco Gomes, up20306498@up.pt
