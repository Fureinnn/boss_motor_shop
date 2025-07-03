<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    header("Location: products.php");
    exit();
}

$product_id = intval($_GET['product_id']);

// Fetch product basic info
$stmt = $mysqli->prepare("SELECT product_name, price FROM product WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: products.php");
    exit();
}

// Fetch product detailed info
$stmt = $mysqli->prepare("SELECT detailed_description, specifications, brand, model, weight, dimensions, features, warranty_info FROM product_info WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product_info = $result->fetch_assoc();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Product Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #1f1f2e;
            color: #f0f0f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem;
        }
        h1, h2, h3 {
            color: #00d4ff;
            font-weight: 700;
        }
        .product-info {
            background-color: #2c2c3c;
            border-radius: 10px;
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }
        .product-info h1 {
            margin-bottom: 1rem;
        }
        .product-info p {
            margin-bottom: 1rem;
        }
        .product-info dt {
            font-weight: 600;
            color: #00b3b3;
        }
        .product-info dd {
            margin-bottom: 1rem;
        }
        a.btn-back {
            display: inline-block;
            margin-bottom: 1rem;
            color: #00d4ff;
            text-decoration: none;
        }
        a.btn-back:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="products.php" class="btn-back">&larr; Back to Products</a>
    <div class="product-info">
        <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
        <h3>Price: <?php echo formatPrice($product['price']); ?></h3>
        <?php if ($product_info): ?>
            <dl>
                <?php if (!empty($product_info['detailed_description'])): ?>
                    <dt>Detailed Description</dt>
                    <dd><?php echo nl2br(htmlspecialchars($product_info['detailed_description'])); ?></dd>
                <?php endif; ?>
                <?php if (!empty($product_info['specifications'])): ?>
                    <dt>Specifications</dt>
                    <dd><?php echo nl2br(htmlspecialchars($product_info['specifications'])); ?></dd>
                <?php endif; ?>
                <?php if (!empty($product_info['brand'])): ?>
                    <dt>Brand</dt>
                    <dd><?php echo htmlspecialchars($product_info['brand']); ?></dd>
                <?php endif; ?>
                <?php if (!empty($product_info['model'])): ?>
                    <dt>Model</dt>
                    <dd><?php echo htmlspecialchars($product_info['model']); ?></dd>
                <?php endif; ?>
                <?php if (!empty($product_info['weight'])): ?>
                    <dt>Weight</dt>
                    <dd><?php echo htmlspecialchars($product_info['weight']); ?></dd>
                <?php endif; ?>
                <?php if (!empty($product_info['dimensions'])): ?>
                    <dt>Dimensions</dt>
                    <dd><?php echo htmlspecialchars($product_info['dimensions']); ?></dd>
                <?php endif; ?>
                <?php if (!empty($product_info['features'])): ?>
                    <dt>Features</dt>
                    <dd><?php echo nl2br(htmlspecialchars($product_info['features'])); ?></dd>
                <?php endif; ?>
                <?php if (!empty($product_info['warranty_info'])): ?>
                    <dt>Warranty Information</dt>
                    <dd><?php echo nl2br(htmlspecialchars($product_info['warranty_info'])); ?></dd>
                <?php endif; ?>
            </dl>
        <?php else: ?>
            <p>No detailed information available for this product.</p>
        <?php endif; ?>
    </div>
</body>
</html>
