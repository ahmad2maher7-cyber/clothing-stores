<div align="center">

**Al-Azhar University - Gaza**
**Faculty of Engineering and Information Technology**
**Department of Information Technology**

<br><br>

![University Logo](../images/university-logo.jpeg)

<br><br>

**FINAL REPORT [ FYP I ]**

**Multi-Vendor Clothing E-Commerce Platform**

<br><br>

**Prepared by:**
Ahmed Maher Al-Talaa (20224159)
Mohammed Rafat Shahin (20221331)
Maram Ayman Al-Matry (20221026)
Nour Mustafa Enaya (20220268)

<br><br>

**Supervised by:**
Dr. Khaled Ismail

<br><br>

**OCTOBER 2026**
**SEMESTER I 2026/2027**

</div>

<div style="page-break-after: always;"></div>

## ACKNOWLEDGEMENT

First and foremost, we extend our praise and gratitude to Allah Almighty for granting us the success, determination, and ability to complete this project as intended.

We are pleased to express our deepest appreciation to **Dr. Khaled Ismail** for his significant role and valuable contributions in supporting this project, reviewing its work, and providing insightful feedback that contributed to its development and enhanced its academic quality.

We would also like to express our appreciation to everyone who offered support throughout the stages of preparing this project, whether through encouragement, feedback, or constructive input, all of which played an important role in the successful completion of this work.

<div style="page-break-after: always;"></div>

## TABLE OF CONTENTS

1. Introduction
   - 1.1 Preamble
   - 1.2 Problem Statement
   - 1.3 Project Objective
   - 1.4 Project Scope
   - 1.5 Project Stages
   - 1.6 Expected Output
   - 1.7 Tools used in this project
2. Requirement Specification
   - 2.1 Business Requirements
   - 2.2 Functional Requirements
   - 2.3 Non-Functional Requirements
   - 2.4 Use Case Diagrams
3. System Design
   - 3.1 System Architecture
   - 3.2 Sequence Diagrams
   - 3.3 Class Diagrams
4. User Interface (UI)
   - 4.1 UI Layout
   - 4.2 Interaction Mechanics
5. Design Website
6. Test Website
7. Conclusion

<div style="page-break-after: always;"></div>

# Chapter 1
# Introduction

## 1.1 Preamble
In light of the rapid expansion of digital commerce and the economic challenges faced by local merchants in the Gaza Strip, digital transformation has become an urgent necessity for business continuity. Small and medium-sized clothing merchants face significant hurdles in establishing standalone e-commerce systems, including high development overhead, infrastructure maintenance, and technical expertise barriers. On the consumer side, shoppers experience fragmented browsing, requiring them to navigate multiple disconnected websites to compare clothing categories, sizes, and promotional discounts.

The **Multi-Vendor Clothing E-Commerce Platform** was developed to bridge this gap. It provides a centralized digital space that unifies independent apparel vendors under a single technical architecture, lowering overhead for merchants while offering consumers a seamless shopping experience. The platform enables merchants to register, configure customized storefronts, and manage inventory effortlessly. Simultaneously, retail shoppers can browse products with advanced filtering, manage shopping carts, apply discount coupons, and complete secure checkouts. Furthermore, the system fully supports Arabic Right-to-Left (RTL) workflows and provides a central administrative control panel to govern vendor onboarding and platform integrity.

## 1.2 Problem Statement
1. **Prohibitive Capital Overhead:** Custom e-commerce engineering requires continuous investments in hosting, security, and maintenance, which deters small merchants from digitalizing.
2. **Marketplace Fragmentation:** Shoppers navigate dozens of disconnected vendor websites, degrading the consumer experience and making price comparison difficult.
3. **Manual Inventory Discrepancies:** Manual tracking across disparate channels leads to stock errors, overselling, and fulfillment delays.
4. **Lack of Reliable Merchant Ratings:** The absence of verified reviews makes it difficult for customers to trust new vendors.
5. **Inadequate Regionalization:** Lack of dedicated regional platforms designed natively for Arabic localization and RTL text direction.

## 1.3 Project Objective
**Main Objective:**
To develop an innovative multi-tenant web application that promotes digital commerce by unifying independent clothing merchants under a scalable, secure, and user-friendly cloud infrastructure.

**Sub-Objectives:**
- **Design a user-friendly interface:** Ensure registration and shopping processes are simple, intuitive, and fully localized for Arabic speakers.
- **Provide automated merchant dashboards:** Allow vendors to manage storefronts, product catalogs, stock, and orders efficiently.
- **Develop a central administrative panel:** Enable platform administrators to govern vendor onboarding, approve stores, and manage users.
- **Implement end-to-end shopping capabilities:** Offer unified carts, dynamic coupon redemption, verified rating systems, and order tracking.
- **Ensure robust security:** Implement Role-Based Access Control (RBAC), data encryption, and secure transaction management.

## 1.4 Project Scope
**1.4.1 Target Users:**
- **Administrator (Admin):** Supervises overall platform activity, approves merchant registrations, and manages privileges.
- **Merchant:** Configures individual storefronts, manages inventory, processes orders, and creates coupons.
- **Customer:** Browses products, maintains wishlists, completes checkouts, and submits reviews.
- **Visitor:** Unauthenticated user capable of browsing published stores and viewing product details.

**1.4.2 Area of Specification:**
The project covers technical dimensions (system architecture, database design, security), economic dimensions (empowering merchants to reduce costs), and social dimensions (enhancing community access to fashion retail).

**1.4.3 Specific Platform:**
The system is implemented as a cloud-deployed web platform (Render). It emphasizes modern web technologies (Laravel 12, Tailwind CSS v4), responsive design for mobile and desktop, and data security using Docker containers.

## 1.5 Project Stages
1. **Analysis and Requirements Gathering:** Identifying program requirements through domain analysis, gathering data on merchant and customer needs, and documenting the Software Requirements Specification (SRS).
2. **Design:** Structuring the system architecture, designing the Entity Relationship Diagram (ERD), and creating UML diagrams (Class, Sequence, Use Case).
3. **Development (Programming):** Implementing the platform using Laravel 12, MySQL 8, and Alpine.js, following the MVC and Service Layer design patterns.
4. **Testing and Integration:** Conducting comprehensive testing (Unit, Feature, Security, Performance) to ensure error-free functionality and data integrity.
5. **Deployment & Maintenance:** Deploying the system on Render, ensuring cloud stability, and planning for future updates.

## 1.6 Expected Output
- **Operational Platform:** A fully functional, cloud-deployed website supporting multiple vendors.
- **Comprehensive Database:** A robust MySQL database enforcing 3NF normalization to manage users, products, and orders.
- **Role-Based Dashboards:** Dedicated interfaces for Admin, Merchants, and Customers.
- **Engineering Documentation:** Complete SRS, ERD, UML diagrams, and Test Matrices.

## 1.7 Tools used in this project
1. **Laravel 12:** A robust PHP framework providing expressive syntax, built-in security, and powerful ORM (Eloquent).
2. **MySQL 8:** A reliable relational database management system for structured data storage.
3. **Tailwind CSS v4:** A utility-first CSS framework used for creating modern, responsive user interfaces.
4. **Alpine.js:** A lightweight JavaScript framework used for dynamic frontend interactions (e.g., shopping carts).
5. **Visual Studio Code:** The primary code editor for development and debugging.
6. **Render:** A cloud hosting platform used to deploy the Dockerized application.
7. **Brevo SMTP:** An email delivery service used for sending authentication and order notifications.

<div style="page-break-after: always;"></div>

# Chapter 2
# Requirement Specification

## 2.1 Business Requirements
- **BR1: Facilitating Digital Commerce:** The platform provides an effective digital space connecting clothing merchants with buyers, enabling organized buying and selling.
- **BR2: Empowering Small Merchants:** By providing a multi-vendor infrastructure, the platform eliminates the need for individual merchants to build and secure their own infrastructure.
- **BR3: Platform Sustainability:** The system incorporates mechanisms to ensure long-term sustainability, such as centralized vendor management and scalable cloud deployment.
- **BR4: Performance Evaluation:** The platform includes analytical dashboards for administrators and merchants to track user activity, sales, and system performance.

## 2.2 Functional Requirements
- **FR1: User Registration and Authentication:** Users shall create accounts and log in securely based on Role-Based Access Control (Admin, Merchant, Customer).
- **FR2: Store & Merchant Onboarding:** Merchants shall submit store creation requests, which require Administrator approval before becoming visible.
- **FR3: Product & Inventory Management:** Merchants shall create, update, and soft-delete product entries, including images and stock levels.
- **FR4: Search and Filtering:** The platform shall provide full-text search and category-based dynamic filtering (price, category, rating).
- **FR5: Shopping Cart & Checkout:** Customers shall manage persistent cart items and process checkout requests with database transactions.
- **FR6: Coupons and Reviews:** Merchants can generate discount coupons; Customers can submit verified reviews on purchased items.
- **FR7: Notifications System:** Users shall receive email notifications for critical events (e.g., password changes, order confirmations).

## 2.3 Non-Functional Requirements
- **Usability:** The interface must be intuitive, highly responsive (Mobile-First), and support Arabic localization with RTL text direction.
- **Performance:** Page rendering latency shall not exceed 2.0 seconds under standard loads.
- **Security:** All passwords must be hashed using bcrypt. State-changing routes must enforce CSRF tokens, and SQL queries must use parameterized statements (Eloquent ORM).
- **Scalability:** The architecture must allow the addition of new features and support concurrent users.

## 2.4 Use Case Diagrams

**UC1: User Registration**
- **Actor:** Visitor
- **Description:** Create a new user account on the platform.
- **Basic Flow:** User opens the registration page → Enters required info (name, email, password) → System validates inputs → Creates account and confirms success.

**UC2: User Login**
- **Actor:** Registered User
- **Description:** Log in to access platform features.
- **Basic Flow:** User opens login page → Enters credentials → System verifies credentials → Grants access and redirects to the appropriate dashboard.

**UC3: Manage Products**
- **Actor:** Merchant
- **Description:** Add or edit items in the store.
- **Basic Flow:** Merchant selects "Add Product" → Enters data (title, price, stock, image) → System validates and publishes → Product becomes visible to customers.

**UC4: Checkout Processing**
- **Actor:** Customer
- **Description:** Complete a purchase.
- **Basic Flow:** Customer inputs shipping details → System validates stock using database transactions → Calculates totals and applies coupons → Creates Order record → Decrements inventory.
- **Exception Flow:** If stock is insufficient, the system rolls back the transaction and displays an inventory error.

**UC5: Approve Store**
- **Actor:** Admin
- **Description:** Review and approve new vendor stores.
- **Basic Flow:** Admin opens pending stores queue → Reviews commercial data → Clicks "Approve" → System updates store status to 'active' and notifies the merchant.

*(Insert the Use Case Diagram Image here - Use the one from your previous PDF)*

<div style="page-break-after: always;"></div>

# Chapter 3
# System Design

## 3.1 System Architecture
The application adopts an N-Tier Model-View-Controller (MVC) enhanced with a Service Layer pattern. This isolates business logic from HTTP controllers and data models, ensuring high maintainability and testability.

- **Presentation Layer:** Blade Templates + Tailwind CSS + Alpine.js.
- **Application Layer:** Laravel Routes, Middleware, Controllers, and Service Classes.
- **Domain Layer:** Eloquent Models (User, Store, Product, Order) and Policies.
- **Infrastructure Layer:** MySQL 8 Database, File Storage, and Brevo SMTP.

*(Insert the System Architecture Diagram here)*

## 3.2 Sequence Diagrams
The dynamic interactions between system components are depicted below.

**Sequence 1: Checkout Processing**
The customer initiates a POST request. The Controller calls the OrderService. The Service opens a Database Transaction, checks stock (using `SELECT FOR UPDATE` to prevent race conditions), inserts the order, decrements stock, commits the transaction, and returns a success page.

*(Insert the Checkout Sequence Diagram here)*

**Sequence 2: Password Change**
The user submits a new password. The Controller verifies the current password via Hash::check, updates the database, and attempts to send a notification email inside a `try/catch` block to prevent server errors (avoiding the 500 error previously encountered).

*(Insert the Password Change Sequence Diagram here)*

## 3.3 Class Diagrams
The object-oriented design highlights entity mappings and primary system domain methods. Core relationships include:
- `User` (1) --- (0..1) `Store`
- `Store` (1) --- (*) `Product`
- `Order` (1) --- (*) `OrderItem`
- `Product` (1) --- (*) `Review`

*(Insert the Class Diagram Image here)*

<div style="page-break-after: always;"></div>

# Chapter 4
# User Interface (UI)

## 4.1 UI Layout Design
The main interface of the Multi-Vendor Clothing Platform is designed to provide a smooth and user-friendly experience, focusing on product discovery, secure checkout, and efficient store management.

1. **Home Page:** Displays a grid of the latest clothing items. Includes a search bar, category filters (Men, Women, Kids), and a prominent cart icon.
2. **Product Details Page:** Shows high-resolution images, size/color variants, price, merchant name, and verified customer reviews.
3. **Merchant Dashboard:** A comprehensive panel displaying sales statistics, a list of active products, and incoming orders.
4. **Admin Dashboard:** A central control panel for the platform owner to monitor total users, active stores, and approve pending merchant requests.

## 4.2 Interaction Mechanics
- **Cart Updates:** Adding items to the cart triggers an asynchronous update (using Alpine.js) without reloading the page.
- **Dynamic Filtering:** Selecting a category or price range instantly updates the product grid.
- **Notifications:** A notification bell in the navigation bar displays unread alerts for order updates or store approvals.

<div style="page-break-after: always;"></div>

# Chapter 5
# Design Website

**1. Welcome Interface (Home Page):**
The main entry point introducing visitors to the platform. It highlights the latest clothing collections, features a prominent search bar, and provides easy access to login or register.
*(Insert Screenshot of your Home Page here)*

**2. Registration Page:**
Allows new customers and merchants to create an account by entering their full name, email address, and a secure password.
*(Insert Screenshot of your Registration Page here)*

**3. Login Page:**
A clean interface enabling registered users to securely access their accounts based on their roles (Admin, Merchant, Customer).
*(Insert Screenshot of your Login Page here)*

**4. Product Details Page:**
Displays detailed information about a selected clothing item, including images, description, stock availability, and the "Add to Cart" button.
*(Insert Screenshot of your Product Details Page here)*

**5. Shopping Cart & Checkout Page:**
Shows the items selected by the customer, allows quantity adjustments, applies valid coupon codes, and collects shipping information.
*(Insert Screenshot of your Cart and Checkout Page here)*

**6. Merchant Dashboard:**
The vendor's control center. It shows total sales, inventory levels, and provides forms to add new clothing items.
*(Insert Screenshot of your Merchant Dashboard here)*

**7. Admin Dashboard:**
The platform administrator's view, featuring global statistics and a moderation queue to approve or reject new vendor stores.
*(Insert Screenshot of your Admin Dashboard here)*

**8. User Profile Page:**
Allows users to view and update their personal information and change their passwords securely.
*(Insert Screenshot of your Profile Page here)*

<div style="page-break-after: always;"></div>

# Chapter 6
# Test Website

**1. Login Validation Test:**
When attempting to log in with an unregistered email, the system correctly blocks access and displays the error message: *"Invalid credentials"*. This confirms the authentication process works securely.
*(Insert Screenshot of the Login Error here)*

**2. Registration Test (Duplicate Email):**
When a user tries to register with an email that already exists in the database, the system rejects the registration and displays: *"This email is already registered"*. This ensures data integrity.
*(Insert Screenshot of the Duplicate Email Error here)*

**3. Add Product Test:**
When a merchant enters valid product data (name, price, image) and clicks "Publish", the system saves it to the database and displays a success message. The product immediately appears in the store.
*(Insert Screenshot of the "Product Added Successfully" message here)*

**4. Checkout Success Test:**
When a customer completes the checkout form with valid data, the system processes the transaction, creates an order record, reduces product stock, and redirects the user to a "Thank You" page with the order number.
*(Insert Screenshot of the Checkout Success page here)*

**5. Password Change Test:**
When a user updates their password from the profile page, the system updates the bcrypt hash and sends a security alert email. The process completes without the 500 Server Error (which was resolved by removing the Queue dependency).
*(Insert Screenshot of the Password Update success message here)*

**6. Search Functionality Test:**
When a user searches for a specific clothing item (e.g., "Shirt"), the system filters the database and displays matching results. If no items match, a *"No results found"* message is displayed.
*(Insert Screenshot of the Search Results here)*

<div style="page-break-after: always;"></div>

# Chapter 7
# Conclusion

The "Multi-Vendor Clothing E-Commerce Platform" represents a meaningful digital solution that responds directly to the economic and technical challenges faced by local apparel merchants. By providing a structured platform for buying and selling, the system reduces financial barriers and encourages efficient inventory management.

The platform's user-friendly interface, robust architecture, and integrated communication channels support smooth coordination between merchants and consumers. The distinction between user roles—utilizing dedicated dashboards for Admins, Merchants, and Customers—ensures proper access control and rapid response times. In addition, the notification system and secure transaction handling enhance user engagement and trust.

Overall, the project contributes to strengthening local digital commerce and supporting small businesses under challenging conditions. The project demonstrates how technology can be leveraged to create practical, sustainable, and commercially impactful solutions that improve access to markets and enhance the quality of life within the community.