<?php
// index.php - Animated Landing Page (Mobile & Desktop Optimized)
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Product Catalog</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #1800ad;
            --accent-gold: #e5b524;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, rgba(24, 0, 173, 0.6) 0%, rgba(10, 0, 80, 0.7) 50%, rgba(229, 181, 36, 0.3) 100%), url('background1.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1e293b;
            margin: 0;
            padding: 1rem;
        }

        .landing-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            max-width: 520px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            border-radius: 18px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin-bottom: 1.25rem;
        }

        .btn-enter {
            background: linear-gradient(135deg, #1800ad, #0c0064);
            border: 2px solid var(--accent-gold);
            color: #ffffff;
            font-weight: 700;
            padding: 0.9rem 2.5rem;
            border-radius: 50px;
            font-size: 1.1rem;
            box-shadow: 0 10px 25px rgba(24, 0, 173, 0.4);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-enter:hover {
            transform: translateY(-3px);
            background: linear-gradient(135deg, #2202db, #1800ad);
            color: var(--accent-gold);
            box-shadow: 0 15px 30px rgba(24, 0, 173, 0.6);
        }

        @media (max-width: 576px) {
            .landing-card {
                padding: 1.75rem 1.25rem;
            }
        }
    </style>
</head>
<body>

    <div class="landing-card">
        <img src="ECC_logo.png" alt="Logo" class="brand-logo" onerror="this.src='https://via.placeholder.com/90?text=ECC'">
        <h1 class="fw-bold mb-2 display-6" style="color: #1800ad; letter-spacing: -0.5px;">E-Commerce Product Catalog</h1>
        <p class="text-muted mb-4 px-md-3 fs-6">Manage products, stock quantities, and inventory seamlessly with a complete CRUD web application.</p>
        
        <a href="catalog.php" class="btn btn-enter w-150 mb-3">
            Click to Enter <i class="bi bi-arrow-right-circle-fill text-warning"></i>
        </a>

        <div class="mt-4 pt-3 border-top">
            <small class="text-muted d-block">Developed by <strong style="color: #1800ad;">Mark Jhondel Casquejo</strong></small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>