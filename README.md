# E-Commerce Product Catalog & Store

A responsive, web-based **Simple E-Commerce Product Catalog** developed for the Web Systems Technologies final project. Built with PHP, MySQL, and Bootstrap 5, the application features a Glassmorphism user interface in royal blue (`#1800ad`) and gold (`#e5b524`), supporting administrative inventory management alongside customer shopping cart and checkout workflows across desktop and mobile viewports.

- **Live Deployment:** [http://ecommerce-catalog.infinityfree.io](http://ecommerce-catalog.infinityfree.io)
- **GitHub Repository:** [https://github.com/markjhondelcasquejo-a11y/ecommerce-catalog](https://github.com/markjhondelcasquejo-a11y/ecommerce-catalog)

---

## Overview

The system separates store browsing and order processing from inventory operations while keeping a consistent visual theme and responsive layout across screen sizes:

- **Customer & Storefront Flow (`index.php`, `catalog.php`, `cart.php`, `checkout.php`):** Browse products, search by keyword, filter by category, add items to a session-backed shopping cart, review line subtotals and grand totals, and complete simulated checkouts with real-time stock adjustments.
- **Inventory Management Flow (`create.php`, `update.php`, `delete.php`):** Add new products, edit existing product information and stock counts, and delete discontinued products with prepared MySQLi statements.

---

## Key Features

- **Glassmorphism Visual Theme:** Frosted glass panels, royal blue (`#1800ad`) and gold (`#e5b524`) accents, custom brand logo (`ECC_logo.png`), background artwork (`background1.png` and `background2.png`), and a live date/time clock display.
- **Mobile & Desktop Responsiveness:** Collapsible Bootstrap 5 navigation bar, stacked summary metric cards (`Total Products`, `Units in Stock`, `Catalog Value`), responsive search and category filter controls, and touch-friendly action buttons.
- **Full CRUD Operations:** Create, read/search/filter, update, and delete products backed by parameterized SQL queries.
- **Session Shopping Cart & Checkout:** Persistent session cart state with item addition, individual item removal, cart clearing, order total calculation, and checkout processing.

---

## Tech Stack

- **Backend:** PHP (MySQLi with prepared statements and PHP sessions)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3 (Glassmorphism styling and responsive media queries), Bootstrap 5, Bootstrap Icons, JavaScript
- **Hosting & Version Control:** InfinityFree (`ecommerce-catalog.infinityfree.io`) and Git / GitHub

---

## Project Structure

```text
ecommerce_catalog/
├── index.php          # Landing page and navigation hub
├── catalog.php        # Product catalog, summary metrics, search, and category filter
├── create.php         # Form and handler to add a new product
├── update.php         # Form and handler to edit an existing product
├── delete.php         # Handler to delete a product from the catalog
├── cart.php           # Session-based shopping cart management
├── checkout.php       # Checkout summary and order completion handler
├── db_connect.php     # Database connection setup
├── ECC_logo.png       # Application logo asset
├── background1.png    # Background visual asset
├── background2.png    # Background visual asset
└── README.md          # Project documentation
```

---

## Database Schema

Create a database in MySQL/MariaDB (for example, `ecommerce_catalog`) and run the following SQL statement to set up the `products` table:

```sql
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    stock_quantity INT NOT NULL DEFAULT 0,
    image_url VARCHAR(500),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Local Setup & Deployment

### 1. Local Development (XAMPP)

1. Clone or copy the project into your local web root:
   ```bash
   git clone https://github.com/markjhondelcasquejo-a11y/ecommerce-catalog.git
   ```
   Place the files inside `C:\xampp\htdocs\ecommerce_catalog` (or your local server directory).
2. Start **Apache** and **MySQL** from the XAMPP Control Panel.
3. Open `phpMyAdmin` (`http://localhost/phpmyadmin`), create your database, and execute the `products` table SQL script above.
4. Update `db_connect.php` with your local database host, username, password, and database name.
5. Open `http://localhost/ecommerce_catalog/` in your browser.

### 2. Pushing Updates to GitHub

```bash
git add .
git commit -m "Update E-Commerce Product Catalog with complete README"
git branch -M main
git push -u origin main
```

### 3. Live Hosting (InfinityFree)

1. Upload all PHP files, image assets (`ECC_logo.png`, `background1.png`, `background2.png`), and `README.md` to the `htdocs` directory on InfinityFree.
2. Import your database schema and sample products into your InfinityFree MySQL database.
3. Update `db_connect.php` with your InfinityFree database credentials and verify the site at `http://ecommerce-catalog.infinityfree.io`.

---

## Author & Submission Info

- **Developer:** Mark Jhondel Casquejo
- **Course:** Web Systems Technologies (Final Project)
- **Institution:** Iloilo State University of Fisheries Science and Technology (ISUFST)
- **Submission Target:** `kenthjoshuaespina@isufst.edu.ph` (November 23–27, 2026)