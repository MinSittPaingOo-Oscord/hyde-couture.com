# Hyde Couture E-commerce Platform

A functional full-stack e-commerce web application built using PHP, custom CSS styling, and a relational database backend. This platform manages user actions, cart mechanisms, custom product parameters, and a powerful administrative backend for order processing and inventory controls.

---

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
│   ├── layout/             # Back-office theme elements (header.php, nav.php, etc.)
│   ├── *category.php       # Category management (add, edit, delete parent/child)
│   ├── *product.php        # Core catalog configurations and temp buffers
│   ├── *order.php          # Detailed administrative order tracking pipelines
│   └── *customer.php       # Profile setups for manual/registered customer logs
│
├── connection/             # Handles core continuous database session configurations
│
├── database/               # Relational SQL schemas and data seeds
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
│
├── index.php               # Front-facing global directory pointer
└── readme.txt              # Standard system legacy execution text notes
```
🛠️ Tech Stack

Backend Processing: PHP

Database Engine: MySQL / PostgreSQL (with relational logic mapping)

User Interface: HTML5, Native CSS (style.css), JavaScript , Bootstrap

Session State: Native PHP Cookie/Session tracking

