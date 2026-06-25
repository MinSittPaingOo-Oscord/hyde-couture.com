# Hyde Couture E-commerce Platform

A functional full-stack e-commerce web application built using PHP, custom CSS styling, and a relational database backend. This platform manages user actions, cart mechanisms, custom product parameters, and a powerful administrative backend for order processing and inventory controls.

---
## Live Demo

Try the application here:

**🌐 Live Demo:** http://hyde-demo.atwebpages.com/

## Test Account

You can use the following test account to explore the system:

**Email:** `ociolinksoftware@gmail.com`
**Password:** `hyde123`


## 🚀 Features

### Customer-Facing Storefront
*   **Product Discovery:** Browse products with specific attribute filtering (categories, sizes, colors).
*   **Shopping Cart System:** Dynamically add items to the cart, modify quantities, and clear items seamlessly.
*   **Order Tracking:** Registered and non-registered users can place orders, upload checkout payment slips, and view their purchase summary/history.
*   **User Profiles:** Customer registration, secure login modals, active profile editing, and favorite items list.

### Robust Admin Dashboard
*   **Inventory & Stock Management:** Complete CRUD actions for handling products, parent/child categories, colors, stocks, and available sizes.
*   **Discount Operations:** Create, update, and manage promotional markdown discounts for products.
*   **Manual Order Handling:** Specialized flows for manually booking orders for non-registered and registered clients alike.
*   **Order Fulfillment:** Process incoming transactions through explicit status steps (Active, Completed, Cancelled, and Failed states).
*   **Performance Reports:** Built-in reporting utilities to observe store progress over time.

---

## 📂 Project Architecture

The directory layout organizes core database utilities, global templates, user views, and isolated administrative components:

```text
HYDE-COUTURE.COM-MAIN/
│
├── admin/                  # Administrative management core panel
│   ├── layout/             # Back-office theme elements (header.php, etc.)
│   ├── *category.php       # Category management (add, edit, delete parent/child)
│   ├── *product.php        # Core catalog configurations and temp buffers
│   ├── *order.php          # Detailed administrative order tracking pipelines
│   └── *customer.php       # Profile setups for manual/registered customer logs
|   └── *others     
│
├── connection/             # Handles core continuous database session configurations
│
├── database/               # The `database.sql` file is included in this folder. If you want to run the web system on your device, import this file into your   database. Please use `utf8mb4_general_ci` as the database collation. (USE utf8mb4_general_ci for Collation TYPE)
│
├── image/                  # Upload directory storing product photos & payment slips
│
├── layout/                 # Global storefront themes (nav.php, footer.php)
│
├── views/                  # Primary user interface routes
│   ├── cart.php            # Active shopping cart state view
│   ├── add_to_cart.php     # Intermediary checkout injection middleware
│   ├── index.php           # Catalog home display dashboard
│   ├── upload_payment.php  # Client transaction slip storage processor
│   └── profile.php         # Customer dashboard & personal preferences data
|   └── *others   
│
├── index.php               # Front-facing global directory pointer
└── readme.txt              # Standard system legacy execution text notes
```
🛠️ Tech Stack

Backend Processing: PHP

Database Engine: MySQL / PostgreSQL (with relational logic mapping)

User Interface: HTML5, Native CSS (style.css), JavaScript , Bootstrap

Session State: Native PHP Cookie/Session tracking

## 📊 System Analysis and Design

The `System Analysis Design` folder contains the system planning and design diagrams for the Hyde Couture E-commerce Platform. These diagrams help explain the system structure, user interactions, data flow, database design, and overall system process.

```text
System Analysis Design/
│
├── Data Flow Diagram.jpg
│   └── Shows how data moves through the system, including product data, customer data, order information, payment records, and admin processes.
│
├── Hyde Admin Panel - Use Case Diagram.jpg
│   └── Describes the main actions available to the admin, such as managing products, categories, stock, discounts, customers, and orders.
│
├── Hyde Customer Side - Use Case Diagram.jpg
│   └── Shows the main customer-side actions, including browsing products, registering accounts, logging in, managing profiles, using the cart, and placing orders.
│
├── Hyde ER Diagram.pdf
│   └── Represents the database structure of the system, including tables, relationships, and how different data entities are connected.
│
├── Hyde Use Case Diagram.png
│   └── Provides an overall use case view of the system and shows how users interact with the main features of the website.
│
└── Hyde context Diagram.jpg
    └── Shows the high-level relationship between the Hyde Couture system, customers, admins, and external system interactions.
```


## 👥 Team Members and Responsibilities

This project was developed collaboratively by the following team members. Each member contributed to specific parts of the **Hyde Couture E-commerce Platform** based on their assigned roles and responsibilities.

| Team Member             | GitHub Username | Role                                                       | Responsibilities                                                                                                                                                                                                                                                  |
| ----------------------- | --------------- | ---------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Lin Khant Min Maung** | `@LinKhantAkaAisai`     | Frontend Developer                                         | Responsible for responsive frontend design on the user-side website. His main contributions include improving the responsive layout and user interface for the navigation bar, registration page, login page, profile page, specific product page, and cart page. |
| **Nandar Kyaw**         | `@nandar372004`     | Frontend Developer                                         | Responsible for responsive frontend design on the user-side website. Her main contributions include working on the home page, footer section, order list page, order details page, and other user-side interface components.                                      |
| **Min Sitt Paing Oo**   | `@MinSittPaingOo-Oscord`     | Backend Developer, Database Administrator, Project Manager | Developed the full Admin Dashboard Panel, except for the product management section. He also handled the account registration system, user-side order list, and order detail features. In addition, he managed database-related tasks and project coordination.   |
| **Kaung Si Thu**        | `@KST-2003`     | Backend Developer                                          | Developed the product management section in the Admin Dashboard Panel. He also handled the complete backend system for the user-side website.                                                                                                                     |

---

## 🤝 Volunteer Contributors

The following volunteers supported the project during the early planning and design stage. Their contributions helped the development team prepare a clear visual direction before starting the actual website implementation.

| Volunteer Name        | GitHub Username | Contribution Area | Responsibilities                                                                                                                                                                                                                                                  |
| --------------------- | --------------- | ----------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **May Myat Noe Phyu** | `@MayMyatNoePhyu`     | UI/UX Design      | Designed the initial website interface using Figma before the development process began. Her contribution helped the team present the website concept to the client, collect feedback, and confirm whether the proposed design matched the client’s requirements. |
| **Min Thu Khaing**    | `MoriartyLink`     | UI/UX Design      | Assisted in preparing the UI/UX design plan through Figma during the project preparation stage. His work supported the team in explaining the website layout, page structure, and design direction to the client before moving into development.                  |


## 🏢 Project Information and Ownership

The **Hyde Couture E-commerce Platform** is a custom website system developed under **Ociolink Company Limited** for the **Hyde Couture Clothing Brand**. This project was created for **Vivian Nora & Lucius Vision** to support online product display, customer account management, order processing, payment slip submission, and admin dashboard management.

All system development, including frontend, backend, database structure, and administrative features, was completed under the ownership and supervision of **Ociolink Company Limited**. The project is designed to help Hyde Couture manage its online sales process more efficiently and provide a better digital shopping experience for customers.

### Company Details

| Information                     | Details                                                                                                               |
| ------------------------------- | --------------------------------------------------------------------------------------------------------------------- |
| **Company Name**                | Ociolink Company Limited                                                                                              |
| **Company Registration Number** | 144736648                                                                                                             |
| **Project Developed For**       | Hyde Couture Clothing Brand                                                                                           |
| **Client Name**                 | Vivian Nora & Lucius Vision                                                                                                 |
| **Project Ownership**           | Ociolink Company Limited                                                                                              |
| **Company Email**               | [ociolinksoftware4@gmail.com](mailto:ociolinksoftware4@gmail.com)                                                     |
| **Company Address**             | No.120/7, 62nd Road, between 18th Street and 19th Street, Dawnabwar Ward, Aungmyaytharzan Township, Mandalay, Myanmar |

### Ownership Notice

This website system is officially developed under **Ociolink Company Limited** for the **Hyde Couture Clothing Brand**. The source code, database structure, project architecture, and related technical implementation are considered part of the project assets managed by Ociolink Company Limited. Any future updates, maintenance, modifications, or system improvements should be handled with permission from the project owner or authorized development team.

