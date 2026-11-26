<?php
// create.php - Add New Product Form with Supplier Cost & Live Unit Profit Preview
require_once 'db_connect.php';
session_start();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name   = trim($_POST['product_name'] ?? '');
    $category       = trim($_POST['category'] ?? '');
    $supplier_cost  = trim($_POST['supplier_cost'] ?? '0');
    $price          = trim($_POST['price'] ?? '');
    $stock_quantity = trim($_POST['stock_quantity'] ?? '');
    $image_url      = trim($_POST['image_url'] ?? '');
    $description    = trim($_POST['description'] ?? '');

    if (empty($product_name)) $errors[] = "Product name is required.";
    if (empty($category)) $errors[] = "Please select a category.";
    if (!is_numeric($supplier_cost) || $supplier_cost < 0) $errors[] = "Valid supplier cost is required.";
    if (!is_numeric($price) || $price < 0) $errors[] = "Valid selling price is required.";
    if (!is_numeric($stock_quantity) || $stock_quantity < 0) $errors[] = "Valid stock quantity is required.";
    if (empty($description)) $errors[] = "Product description is required.";

    if (empty($image_url)) {
        $image_url = "https://via.placeholder.com/300x180?text=No+Image";
    }

    if (empty($errors)) {
        $cost_val  = (float)$supplier_cost;
        $price_val = (float)$price;
        $stock_val = (int)$stock_quantity;

        $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, supplier_cost, stock_quantity, image_url, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddiss", $product_name, $category, $price_val, $cost_val, $stock_val, $image_url, $description);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: catalog.php?msg=added");
            exit();
        } else {
            $errors[] = "Database error: " . $stmt->error;
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - E-Commerce Product Catalog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--glass-border);
            border-radius: 18px;
            box-shadow: var(--glass-shadow);
        }

        .glass-header {
            background: rgba(24, 0, 173, 0.92) !important;
            backdrop-filter: blur(12px);
            border-bottom: 2px solid var(--accent-gold) !important;
            color: #ffffff;
            border-top-left-radius: 18px !important;
            border-top-right-radius: 18px !important;
            padding: 1.1rem 1.35rem;
        }

        .btn-gold {
            background: linear-gradient(135deg, #e5b524, #d49b0c);
            border: none;
            color: #1800ad;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(229, 181, 36, 0.3);
            transition: all 0.2s ease;
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(229, 181, 36, 0.6);
            background: linear-gradient(135deg, #f3c433, #e5b524);
            color: #1800ad;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.7rem 1rem;
            color: #1e293b;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            background: #ffffff !important;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px rgba(229, 181, 36, 0.25);
        }
        .form-label {
            font-weight: 600;
            color: #1800ad;
            font-size: 0.88rem;
        }

        .profit-preview-box {
            background: rgba(24, 0, 173, 0.06);
            border: 1px dashed rgba(24, 0, 173, 0.28);
            border-radius: 12px;
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

                    <a class="nav-link text-light px-2 fw-semibold" href="catalog.php"><i class="bi bi-grid me-1"></i>Catalog</a>
                    <a class="btn btn-outline-light px-3 py-1.5 rounded-pill fw-bold text-nowrap" href="catalog.php">
                        <i class="bi bi-arrow-left me-1"></i> Back to Catalog
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger shadow-sm rounded-3 p-3 mb-4">
                        <ul class="mb-0 small">
                            <?php foreach ($errors as $err): ?>
                                <li><?php echo htmlspecialchars($err); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="glass-panel overflow-hidden">
                    <div class="glass-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold fs-5"><i class="bi bi-plus-circle-fill me-2 text-warning"></i>Add New Product</h5>
                        <a href="catalog.php" class="btn btn-sm btn-outline-light rounded-pill px-3">Cancel</a>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="create.php">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-7">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" name="product_name" class="form-control" required placeholder="e.g., Wireless Bluetooth Headphones" value="<?php echo htmlspecialchars($_POST['product_name'] ?? ''); ?>">
                                </div>
                                <div class="col-12 col-md-5">
                                    <label class="form-label">Category *</label>
                                    <select name="category" class="form-select" required>
                                        <option value="">Select category</option>
                                        <option value="Electronics" <?php if(($_POST['category'] ?? '') == 'Electronics') echo 'selected'; ?>>Electronics</option>
                                        <option value="Fashion" <?php if(($_POST['category'] ?? '') == 'Fashion') echo 'selected'; ?>>Fashion</option>
                                        <option value="Home & Living" <?php if(($_POST['category'] ?? '') == 'Home & Living') echo 'selected'; ?>>Home & Living</option>
                                        <option value="Beauty & Health" <?php if(($_POST['category'] ?? '') == 'Beauty & Health') echo 'selected'; ?>>Beauty & Health</option>
                                        <option value="Sports & Outdoors" <?php if(($_POST['category'] ?? '') == 'Sports & Outdoors') echo 'selected'; ?>>Sports & Outdoors</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-sm-4">
                                    <label class="form-label">Supplier Cost (₱) *</label>
                                    <input type="number" step="0.01" min="0" name="supplier_cost" id="supplier_cost" class="form-control" required placeholder="0.00" value="<?php echo htmlspecialchars($_POST['supplier_cost'] ?? '0.00'); ?>">
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="form-label">Selling Price (₱) *</label>
                                    <input type="number" step="0.01" min="0" name="price" id="selling_price" class="form-control" required placeholder="0.00" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                                </div>
                                <div class="col-6 col-sm-4">
                                    <label class="form-label">Stock Qty *</label>
                                    <input type="number" min="0" name="stock_quantity" class="form-control" required placeholder="0" value="<?php echo htmlspecialchars($_POST['stock_quantity'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="profit-preview-box p-3 mb-3 d-flex justify-content-between align-items-center">
                                <span class="small fw-semibold text-secondary">
                                    <i class="bi bi-calculator me-1 text-primary"></i>Estimated Profit per Unit:
                                </span>
                                <span class="fw-bold fs-6 text-success" id="unit-profit-preview">₱0.00</span>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Product Image URL (Optional)</label>
                                <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg or ECC_logo.png" value="<?php echo htmlspecialchars($_POST['image_url'] ?? ''); ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Product Description *</label>
                                <textarea name="description" class="form-control" rows="4" required placeholder="Enter product specifications..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-flex flex-column flex-md-row justify-content-end gap-2">
                                <a href="catalog.php" class="btn btn-light rounded-pill px-4 fw-semibold border w-100 w-md-auto text-center py-2">Cancel</a>
                                <button type="submit" class="btn btn-gold rounded-pill px-5 py-2.5 w-100 w-md-auto shadow">Save Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-3 mt-auto" style="background: rgba(10, 0, 70, 0.9); backdrop-filter: blur(10px); color: #ffffff; border-top: 2px solid rgba(229, 181, 36, 0.4);">
        <div class="container">
            <small class="opacity-75" style="font-size: 0.8rem;">E-Commerce Product Catalog &bull; Developed by <strong class="text-warning">Mark Jhondel Casquejo</strong></small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateClock() {
            const now = new Date();
            const options = {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('liveClock').innerText = now.toLocaleDateString('en-US', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        (function () {
            const costInput = document.getElementById('supplier_cost');
            const priceInput = document.getElementById('selling_price');
            const previewEl = document.getElementById('unit-profit-preview');

            function updateProfitPreview() {
                const cost = parseFloat(costInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const profit = price - cost;
                previewEl.textContent = '₱' + profit.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                previewEl.classList.toggle('text-success', profit >= 0);
                previewEl.classList.toggle('text-danger', profit < 0);
            }

            if (costInput && priceInput && previewEl) {
                costInput.addEventListener('input', updateProfitPreview);
                priceInput.addEventListener('input', updateProfitPreview);
                updateProfitPreview();
            }
        })();
    </script>
</body>
</html>