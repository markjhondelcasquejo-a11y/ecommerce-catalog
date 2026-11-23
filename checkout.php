<?php
// checkout.php - Checkout & Order Completion Simulation (Mobile & Web Optimized)
session_start();
require_once 'db_connect.php';

if (empty($_SESSION['cart'])) {
    header("Location: catalog.php");
    exit();
}

$order_placed = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $payment_method = $_POST['payment_method'];
    
    // Clear cart upon successful order
    $_SESSION['cart'] = [];
    $order_placed = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - E-Commerce Product Catalog</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #1800ad; --accent-gold: #e5b524; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, rgba(24, 0, 173, 0.5) 0%, rgba(10, 0, 80, 0.6) 50%, rgba(229, 181, 36, 0.25) 100%), url('background2.png') no-repeat center center fixed;
            background-size: cover; min-height: 100vh; color: #1e293b;
        }
        .glass-panel { background: rgba(255, 255, 255, 0.88); backdrop-filter: blur(20px); border-radius: 18px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25); }
        .btn-gold { background: linear-gradient(135deg, #e5b524, #d49b0c); border: none; color: #1800ad; font-weight: 700; }
        .btn-gold:hover { background: linear-gradient(135deg, #f3c433, #e5b524); color: #1800ad; }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(0, 0, 0, 0.1);
            font-size: 0.95rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <div class="container my-auto py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="glass-panel p-4 p-md-5">
                    <?php if ($order_placed): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-check-circle-fill text-success display-1 mb-3"></i>
                            <h3 class="fw-bold mb-3 fs-4" style="color: #1800ad;">Order Placed Successfully!</h3><h3 class="fw-bold mb-2 fs-4" style="color: #1800ad;">Order #<?php echo htmlspecialchars((string)$placed_order_id); ?> Placed!</h3>
                            <p class="text-muted mb-4 small">Thank you for your purchase. Your simulated order has been recorded and will be processed for delivery.</p>
                            <a href="catalog.php" class="btn btn-gold px-5 py-2.5 rounded-pill fw-bold shadow w-100 w-md-auto">Return to Catalog</a>
                        </div>
                    <?php else: ?>
                        <h4 class="fw-bold mb-4 fs-5" style="color: #1800ad;"><i class="bi bi-shield-lock-fill me-2"></i>Checkout & Shipping</h4>
                        <form method="POST" action="checkout.php">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Full Name</label>
                                <input type="text" name="fullname" class="form-control rounded-pill py-2 px-3" required placeholder="Mark Jhondel R. Casquejo">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Delivery Address</label>
                                <textarea name="address" class="form-control rounded-4 py-2 px-3" rows="3" required placeholder="Poblacion, Barotac Nuevo, Iloilo"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Phone Number</label>
                                <input type="text" name="phone" class="form-control rounded-pill py-2 px-3" required placeholder="09123456789">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold small">Payment Method</label>
                                <select name="payment_method" class="form-select rounded-pill py-2 px-3">
                                    <option value="COD">Cash on Delivery (COD)</option>
                                    <option value="GCash">GCash Digital Wallet</option>
                                    <option value="Bank">Direct Bank Transfer</option>
                                </select>
                            </div>
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 pt-2">
                                <a href="cart.php" class="text-decoration-none text-muted fw-semibold small w-100 w-md-auto text-center text-md-start"><i class="bi bi-arrow-left me-1"></i>Back to Cart</a>
                                <button type="submit" class="btn btn-gold px-4 py-2.5 rounded-pill shadow fw-bold w-100 w-md-auto">Place Order Now</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>