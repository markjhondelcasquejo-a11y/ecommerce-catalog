<?php
// catalog.php - Main Dashboard (Modern Card-Based User Friendly UI & Mobile Compatible)
require_once 'db_connect.php';
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Search and Category Filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? trim($_GET['category']) : '';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (product_name LIKE ? OR description LIKE ?)";
    $search_param = "%" . $search . "%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

$sql .= " ORDER BY product_id ASC";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Summary Metrics Query
$stats_query = mysqli_query($conn, "SELECT COUNT(*) as total_items, SUM(stock_quantity) as total_stock, SUM(price * stock_quantity) as total_value FROM products");
$stats = mysqli_fetch_assoc($stats_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - E-Commerce Product Catalog</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1800ad;
            --accent-gold: #e5b524;
            --glass-bg: rgba(255, 255, 255, 0.88);
            --glass-border: rgba(255, 255, 255, 0.95);
            --glass-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, rgba(24, 0, 173, 0.5) 0%, rgba(10, 0, 80, 0.6) 50%, rgba(229, 181, 36, 0.25) 100%), url('background2.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #1e293b;
        }

        /* Frosted Glass Navbar */
        .navbar-custom {
            background: rgba(24, 0, 173, 0.94) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 2px solid rgba(229, 181, 36, 0.4);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 0.75rem 1rem;
        }
        .navbar-logo {
            width: 38px;
            height: 38px;
            object-fit: contain;
            border-radius: 8px;
        }
        .navbar-brand-text {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff !important;
        }

        /* Glassmorphism Containers */
        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            box-shadow: var(--glass-shadow);
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(12px);
            border-bottom: 2px solid rgba(229, 181, 36, 0.5) !important;
            color: var(--primary-blue);
            border-top-left-radius: 18px !important;
            border-top-right-radius: 18px !important;
            padding: 1.1rem 1.35rem;
        }

        /* Stat Cards */
        .card-stat {
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }
        .stat-royal {
            background: linear-gradient(135deg, rgba(24, 0, 173, 0.94), rgba(12, 0, 100, 0.98));
            color: #ffffff;
            border-bottom: 4px solid var(--accent-gold);
        }
        .stat-gold {
            background: linear-gradient(135deg, rgba(229, 181, 36, 0.96), rgba(212, 155, 12, 0.96));
            color: #1800ad;
            border-bottom: 4px solid var(--primary-blue);
        }
        .stat-dark {
            background: linear-gradient(135deg, rgba(15, 5, 60, 0.92), rgba(5, 0, 35, 0.96));
            color: #ffffff;
            border-bottom: 4px solid var(--accent-gold);
        }
        .stat-label {
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .stat-value {
            font-size: 1.35rem;
            font-weight: 800;
        }

        @media (min-width: 768px) {
            .stat-value { font-size: 1.6rem; }
        }

        /* Product Card UI */
        .product-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 30px rgba(24, 0, 173, 0.15);
            border-color: var(--accent-gold);
        }
        .product-card-img-wrapper {
            position: relative;
            height: 180px;
            background: #f8fafc;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .product-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-card-img {
            transform: scale(1.05);
        }
        .product-card-body {
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        /* Buttons & Actions */
        .btn-gold {
            background: linear-gradient(135deg, #e5b524, #d49b0c);
            border: none;
            color: #1800ad;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(229, 181, 36, 0.3);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #f3c433, #e5b524);
            color: #1800ad;
        }
        .btn-cart-buy {
            background: rgba(24, 0, 173, 0.9);
            color: #ffffff;
            font-weight: 600;
            border-radius: 10px;
            border: none;
        }
        .btn-cart-buy:hover {
            background: var(--primary-blue);
            color: var(--accent-gold);
        }
        .btn-icon-action {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px rgba(229, 181, 36, 0.25);
        }

        .live-clock-badge {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #ffffff;
            font-size: 0.78rem;
            padding: 0.35rem 0.75rem;
            border-radius: 10px;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Responsive Navigation Bar with Mobile Hamburger Toggle -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid px-3">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <img src="ECC_logo.png" alt="Logo" class="navbar-logo me-2" onerror="this.src='https://via.placeholder.com/38?text=ECC'">
                <span class="navbar-brand-text">E-Commerce Catalog</span>
            </a>
            
            <button class="navbar-toggler border-0 shadow-none text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse justify-content-end mt-2 mt-lg-0" id="navbarNav">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
                    <div class="live-clock-badge d-none d-xl-flex align-items-center">
                        <i class="bi bi-clock-history text-warning me-1"></i>
                        <span id="liveClock">Loading...</span>
                    </div>

                    <a class="nav-link text-light px-2 fw-semibold" href="index.php"><i class="bi bi-house-door me-1"></i>Home</a>
                    <a class="nav-link active fw-bold text-warning px-2" href="catalog.php"><i class="bi bi-grid me-1"></i>Catalog</a>
                    
                    <a class="btn btn-gold px-3 py-1.5 rounded-pill fw-bold text-nowrap" href="cart.php">
                        <i class="bi bi-cart-fill me-1"></i> Cart (<?php echo array_sum($_SESSION['cart']); ?>)
                    </a>

                    <a class="btn btn-outline-light px-3 py-1.5 rounded-pill fw-bold text-nowrap" href="create.php">
                        <i class="bi bi-plus-circle-fill me-1"></i> Add Product
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container py-4 flex-grow-1">
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 text-dark fw-bold rounded-3 p-3 mb-3" role="alert">
                <i class="bi bi-bell-fill me-2"></i>
                <?php
                    if ($_GET['msg'] == 'added') echo "Success! New product added to catalog.";
                    if ($_GET['msg'] == 'updated') echo "Updated! Product details have been saved.";
                    if ($_GET['msg'] == 'deleted') echo "Deleted! Product removed from catalog.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Stacked Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card card-stat stat-royal">
                    <div class="card-body p-3 text-center text-md-start">
                        <span class="text-uppercase opacity-75 d-block mb-1 stat-label">Total Products</span>
                        <h3 class="mb-0 stat-value"><?php echo $stats['total_items'] ?? 0; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-stat stat-gold">
                    <div class="card-body p-3 text-center text-md-start">
                        <span class="text-uppercase fw-bold d-block mb-1 stat-label" style="color: #1800ad; opacity: 0.85;">Units in Stock</span>
                        <h3 class="mb-0 text-dark stat-value"><?php echo number_format($stats['total_stock'] ?? 0); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-stat stat-dark">
                    <div class="card-body p-3 text-center text-md-start">
                        <span class="text-uppercase opacity-75 d-block mb-1 stat-label">Catalog Value</span>
                        <h3 class="mb-0 text-warning stat-value">₱<?php echo number_format($stats['total_value'] ?? 0, 2); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Search & Filter Toolbar -->
        <div class="glass-panel p-3 mb-4">
            <form method="GET" action="catalog.php" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-0 ps-3 rounded-start-pill"><i class="bi bi-search text-primary"></i></span>
                        <input type="text" name="search" class="form-control border-0 rounded-end-pill shadow-none" placeholder="Search product name..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select name="category" class="form-select border-0 rounded-pill shadow-none">
                        <option value="">All Categories</option>
                        <option value="Electronics" <?php if($category_filter == 'Electronics') echo 'selected'; ?>>Electronics</option>
                        <option value="Fashion" <?php if($category_filter == 'Fashion') echo 'selected'; ?>>Fashion</option>
                        <option value="Home & Living" <?php if($category_filter == 'Home & Living') echo 'selected'; ?>>Home & Living</option>
                        <option value="Beauty & Health" <?php if($category_filter == 'Beauty & Health') echo 'selected'; ?>>Beauty & Health</option>
                        <option value="Sports & Outdoors" <?php if($category_filter == 'Sports & Outdoors') echo 'selected'; ?>>Sports & Outdoors</option>
                    </select>
                </div>
                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-gold w-50 rounded-pill py-2">Filter</button>
                    <a href="catalog.php" class="btn btn-light w-50 rounded-pill py-2 fw-semibold border text-center">Reset</a>
                </div>
            </form>
        </div>

        <!-- Catalog Header Section -->
        <div class="glass-panel overflow-hidden mb-5">
            <div class="glass-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold fs-5"><i class="bi bi-shop me-2 text-primary"></i>Explore Products & Store</h5>
                <span class="badge fw-bold px-3 py-2 rounded-pill shadow-sm" style="background-color: #1800ad; color: #e5b524; font-size: 0.8rem;">
                    <?php echo $result->num_rows; ?> items found
                </span>
            </div>

            <div class="p-3 p-md-4">
                <?php if ($result->num_rows > 0): ?>
                    <div class="row g-4">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="product-card">
                                    <div class="product-card-img-wrapper">
                                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product" class="product-card-img" onerror="this.src='https://via.placeholder.com/300x180?text=No+Image'">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <?php if ($row['stock_quantity'] == 0): ?>
                                                <span class="badge bg-danger shadow-sm px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">Out of Stock</span>
                                            <?php elseif ($row['stock_quantity'] <= 5): ?>
                                                <span class="badge shadow-sm px-2.5 py-1 rounded-pill" style="background-color: #e5b524; color: #1800ad; font-weight: 700; font-size: 0.7rem;">Low Stock (<?php echo $row['stock_quantity']; ?>)</span>
                                            <?php else: ?>
                                                <span class="badge bg-success shadow-sm px-2.5 py-1 rounded-pill" style="font-size: 0.7rem;">In Stock (<?php echo $row['stock_quantity']; ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge fw-semibold px-2.5 py-1 rounded-pill shadow-sm" style="background: rgba(24, 0, 173, 0.85); color: #e5b524; font-size: 0.7rem;">
                                                <?php echo htmlspecialchars($row['category']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="product-card-body">
                                        <h6 class="fw-bold mb-1" style="color: #1800ad; font-size: 1.05rem;"><?php echo htmlspecialchars($row['product_name']); ?></h6>
                                        <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            <?php echo htmlspecialchars($row['description']); ?>
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                            <div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Price</small>
                                                <span class="fw-bold text-success fs-5">₱<?php echo number_format($row['price'], 2); ?></span>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <a href="cart.php?action=add&id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-icon-action btn-cart-buy shadow-sm" title="Add to Cart">
                                                    <i class="bi bi-cart-plus-fill"></i>
                                                </a>
                                                <a href="update.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-icon-action btn-light border text-primary shadow-sm" title="Edit Product">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="delete.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-icon-action btn-light border text-danger shadow-sm" onclick="return confirm('Are you sure you want to delete this product?');" title="Delete Product">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-search display-4 text-primary opacity-50 mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark">No products found</h5>
                        <p class="small text-muted mb-4">Try checking your search keywords or choosing a different category filter.</p>
                        <a href="catalog.php" class="btn btn-gold px-4 py-2 rounded-pill">Reset Filters</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 mt-auto" style="background: rgba(10, 0, 70, 0.9); color: #ffffff; border-top: 2px solid rgba(229, 181, 36, 0.4);">
        <div class="container">
            <small class="opacity-75" style="font-size: 0.8rem;">E-Commerce Product Catalog &bull; Developed by <strong class="text-warning">Mark Jhondel Casquejo</strong></small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Real-time Clock Script -->
    <script>
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'short', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('liveClock').innerText = now.toLocaleDateString('en-US', options);
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>