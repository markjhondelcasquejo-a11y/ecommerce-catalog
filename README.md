# 🛒 E-Commerce Product Catalog & Store

A modern, responsive web-based **Simple E-Commerce Product Catalog** built with PHP, MySQL, and Bootstrap 5. Designed with an advanced Glassmorphism UI in Royal Blue and Gold, this system supports both administrative inventory management and customer shopping workflows across desktop and mobile devices.

**Live Demo:** [http://ecommerce-catalog.infinityfree.io](http://ecommerce-catalog.infinityfree.io)

---

## ✨ Key Features

- **Modern Glassmorphism UI:** Frosted glass panels, royal blue (`#1800ad`) and gold (`#e5b524`) accents, custom branding (`ECC_logo.png`), and real-time clock/date widgets.
- **Fully Responsive Design:** Mobile-first card grids, collapsible hamburger navigation, stacked metric summaries, and touch-optimized action buttons for smartphones, tablets, and widescreen desktops.
- **Inventory Management (CRUD):**
  - **Create (`create.php`):** Add new products with category selection, pricing, stock levels, image URLs, and descriptions.
  - **Read & Filter (`catalog.php`):** Real-time search by product name/description, category filtering, and live inventory metrics (Total Products, Units in Stock, Total Catalog Value).
  - **Update (`update.php`):** Edit existing product details and restock inventory.
  - **Delete (`delete.php`):** Remove obsolete or discontinued items safely.
- **Customer Shopping Workflow:**
  - **Shopping Cart (`cart.php`):** Add items to cart, view subtotals, remove individual items, or clear the cart using PHP sessions.
  - **Checkout Simulation (`checkout.php`):** Review order summaries and complete simulated purchases with automatic stock deduction.

---

## 🛠️ Tech Stack

- **Frontend:** HTML5, CSS3 (Custom Glassmorphism & Responsive Media Queries), Bootstrap 5.3, Bootstrap Icons, JavaScript (ES6)
- **Backend:** PHP 8+ (Prepared Statements with MySQLi)
- **Database:** MySQL / MariaDB
- **Hosting:** InfinityFree (`http://ecommerce-catalog.infinityfree.io`)

---

## 📂 Project Structure

```text
ecommerce_catalog/
├── index.php           # Landing page & system overview
├── catalog.php         # Main product catalog, metrics dashboard & search/filter
├── create.php          # Add new product form
├── update.php          # Edit existing product details
├── delete.php          # Delete product handler
├── cart.php            # Session-based shopping cart
├── checkout.php        # Order checkout & stock update handler
├── db_connect.php      # MySQL database connection configuration
├── ECC_logo.png        # Custom application logo
├── background1.png     # Landing page background visual
├── background2.png     # Dashboard & catalog background visual
└── README.md           # Project documentation