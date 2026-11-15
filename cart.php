<?php
// cart.php - Shopping Cart & Checkout Simulation (Modern Card UI & Mobile Compatible)
session_start();
require_once 'db_connect.php';

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart action from catalog
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $product_id = intval($_GET['id']);
    $qty = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
    header("Location: cart.php?msg=added");
    exit();
}

// Handle Remove item action
if (isset($_GET['action']) && $_GET['action'] == 'remove') {
    $product_id = intval($_GET['id']);
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php?msg=removed");
    exit();
}

// Handle Clear cart action
if (isset($_GET['action']) && $_GET['action'] == 'clear') {
    $_SESSION['cart'] = [];
    header("Location: cart.php?msg=cleared");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - E-Commerce Product Catalog</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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
        .navbar-custom {
            background: rgba(24, 0, 173, 0.94) !important;
            backdrop-filter: blur(16px);
            border-bottom: 2px solid rgba(229, 181, 36, 0.4);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            padding: 0.75rem 1rem;
        }
        .navbar-logo {
            width: 38px; height: 38px; object-fit: contain; border-radius: 8px;
        }
        .navbar-brand-text {
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff !important;
        }
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
        .btn-gold {
            background: linear-gradient(135deg, #e5b524, #d49b0c);
            border: none; color: #1800ad; font-weight: 700;
            box-shadow: 0 4px 12px rgba(229, 181, 36, 0.3);
        }
        .btn-gold:hover { background: linear-gradient(135deg, #f3c433, #e5b524); color: #1800ad; }
        .cart-item-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 14px;
            padding: 1rem;
            transition: transform 0.2s ease;
        }
        .cart-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        }
        .product-img { width: 64px; height: 64px; object-fit: cover; border-radius: 12px; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Responsive Navigation Bar -->
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
                    <a class="nav-link text-light px-2 fw-semibold" href="catalog.php"><i class="bi bi-grid me-1"></i>Catalog</a>
                    <a class="btn btn-gold px-3 py-1.5 rounded-pill fw-bold text-nowrap" href="cart.php">
                        <i class="bi bi-cart-fill me-1"></i> Cart (<?php echo array_sum($_SESSION['cart']); ?>)
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container py-4 flex-grow-1">
        
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 text-dark fw-bold rounded-3 p-3 mb-3" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <?php
                    if ($_GET['msg'] == 'added') echo "Product successfully added to your cart!";
                    if ($_GET['msg'] == 'removed') echo "Item removed from cart.";
                    if ($_GET['msg'] == 'cleared') echo "Shopping cart cleared.";
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="glass-panel overflow-hidden mb-5">
            <div class="glass-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold fs-5"><i class="bi bi-cart3 me-2 text-primary"></i>Your Shopping Cart</h5>
                <?php if (!empty($_SESSION['cart'])): ?>
                    <a href="cart.php?action=clear" class="btn btn-sm btn-outline-danger rounded-pill px-3">Clear Cart</a>
                <?php endif; ?>
            </div>
            
            <div class="card-body p-3 p-md-4">
                <?php if (empty($_SESSION['cart'])): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-3 text-primary opacity-50 mb-3"></i>
                        <h4 class="fw-bold text-dark fs-5">Your cart is currently empty</h4>
                        <p class="text-muted small mb-4">Explore our product catalog and add items to your cart to begin shopping.</p>
                        <a href="catalog.php" class="btn btn-gold px-4 py-2 rounded-pill">Browse Catalog</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3 mb-4">
                        <?php 
                        $grand_total = 0;
                        foreach ($_SESSION['cart'] as $pid => $qty):
                            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                            $stmt->bind_param("i", $pid);
                            $stmt->execute();
                            $product = $stmt->get_result()->fetch_assoc();
                            
                            if ($product):
                                $subtotal = $product['price'] * $qty;
                                $grand_total += $subtotal;
                        ?>
                            <div class="cart-item-card d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="product-img" onerror="this.src='https://via.placeholder.com/64?text=Img'">
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #1800ad;"><?php echo htmlspecialchars($product['product_name']); ?></h6>
                                        <span class="badge rounded-pill bg-light text-secondary border mb-1" style="font-size: 0.7rem;"><?php echo htmlspecialchars($product['category']); ?></span>
                                        <div class="small text-muted">Price: ₱<?php echo number_format($product['price'], 2); ?></div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-4 w-100 w-sm-auto border-top border-sm-top-0 pt-2 pt-sm-0">
                                    <div class="text-center">
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Quantity</small>
                                        <span class="badge bg-secondary px-2.5 py-1"><?php echo $qty; ?></span>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Subtotal</small>
                                        <span class="fw-bold text-success">₱<?php echo number_format($subtotal, 2); ?></span>
                                    </div>
                                    <div>
                                        <a href="cart.php?action=remove&id=<?php echo $pid; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1" title="Remove item">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-top pt-4 gap-3">
                        <div class="w-100 w-md-auto">
                            <a href="catalog.php" class="btn btn-outline-primary rounded-pill px-4 w-100 w-md-auto">
                                <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                        <div class="text-center text-md-end w-100 w-md-auto">
                            <h5 class="fw-bold mb-3 fs-5">Total Amount: <span class="text-success">₱<?php echo number_format($grand_total, 2); ?></span></h5>
                            <a href="checkout.php" class="btn btn-gold btn-lg px-5 rounded-pill shadow w-100 w-md-auto">
                                Proceed to Checkout <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 mt-auto" style="background: rgba(10, 0, 70, 0.9); color: #ffffff; border-top: 2px solid rgba(229, 181, 36, 0.4);">
        <div class="container"><small class="opacity-75" style="font-size: 0.8rem;">E-Commerce Product Catalog &bull; Developed by <strong class="text-warning">Mark Jhondel Casquejo</strong></small></div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>