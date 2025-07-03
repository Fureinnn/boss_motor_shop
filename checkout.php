<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Redirect to cart if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details for autofill
$stmt = $mysqli->prepare("SELECT fullname, email, phone, street, city, state, zip, country FROM user WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch cart items for the user
$stmt = $mysqli->prepare("SELECT ci.quantity, ci.unit_price as price, p.product_name as name FROM cart c JOIN cart_item ci ON c.cart_id = ci.cart_id JOIN product p ON ci.product_id = p.product_id WHERE c.user_id = ? AND c.status = 'active'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}

$errors = [];
$success = '';

// Prepare shipping address autofill
$shipping_address_autofill = '';
if ($user) {
    $address_parts = array_filter([$user['street'], $user['city'], $user['state'], $user['zip'], $user['country']]);
    $shipping_address_autofill = implode(", ", $address_parts);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $shipping_address = sanitize($_POST['shipping_address'] ?? '');
    $payment_method = sanitize($_POST['payment_method'] ?? '');

    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }
    if (empty($shipping_address)) {
        $errors[] = "Shipping address is required.";
    }
    if (empty($payment_method) || !in_array($payment_method, ['credit_card', 'paypal', 'cod'])) {
        $errors[] = "Please select a valid payment method.";
    }

    if (empty($errors)) {
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
        $tax_rate = 0.10; // 10% tax rate
        $tax_amount = $total_amount * $tax_rate;
        $shipping_cost = 0;

        $order_number = uniqid('ORD-');
        $payment_status = ($payment_method === 'cod') ? 'pending' : 'paid';
        $order_status = 'pending';

        $stmt = $mysqli->prepare("INSERT INTO `order` (user_id, order_number, total_amount, tax_amount, shipping_cost, order_status, payment_status, payment_method, shipping_address, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isdddssss", $user_id, $order_number, $total_amount, $tax_amount, $shipping_cost, $order_status, $payment_status, $payment_method, $shipping_address);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            // TODO: Insert order items into order_items table if exists

            // Clear cart items from database
            $stmt_clear = $mysqli->prepare("DELETE ci FROM cart_item ci JOIN cart c ON ci.cart_id = c.cart_id WHERE c.user_id = ?");
            $stmt_clear->bind_param("i", $user_id);
            $stmt_clear->execute();
            $stmt_clear->close();

            header("Location: thankyou.php?order=" . urlencode($order_number));
            exit();

            $success = "Order placed successfully! Your order number is: " . htmlspecialchars($order_number);
        } else {
            $errors[] = "Failed to place order. Please try again.";
        }
        $stmt->close();
    }
}

function displayOrderSummary() {
    if (empty($GLOBALS['cart_items'])) {
        echo "<p>Your cart is empty.</p>";
        return;
    }
    echo "<table class='table table-dark table-striped'>";
    echo "<thead><tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr></thead><tbody>";
    $total = 0;
    foreach ($GLOBALS['cart_items'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        echo "<tr>";
        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
        echo "<td>" . intval($item['quantity']) . "</td>";
        echo "<td>" . formatPrice($item['price']) . "</td>";
        echo "<td>" . formatPrice($subtotal) . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "<tfoot class='table-secondary'><tr><td colspan='3'>Total</td><td>" . formatPrice($total) . "</td></tr></tfoot>";
    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Checkout - Boss Motor Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1f1f2e;
            color: #f0f0f0;
        }
        
        .navbar, footer {
            background-color: #0d3b3f;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: #00d4ff;
        }
        
        .form-container {
            background-color: #2c2c3c;
            border: 1px solid #444;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .form-control, .form-control:focus {
            background-color: #3a3a4a;
            border-color: #444;
            color: #f0f0f0;
        }
        
        .form-label {
            color: #00d4ff;
        }
        
        .form-check-label {
            color: #f0f0f0;
        }
        
        .btn-primary {
            background-color: #00b3b3;
            border-color: #00b3b3;
        }
        
        .btn-primary:hover {
            background-color: #009999;
            border-color: #009999;
        }
        
        .card {
            background-color: #2c2c3c;
            border: 1px solid #444;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .alert-danger {
            background-color: #4a1c1c;
            border-color: #5c2525;
            color: #ff8c8c;
        }
        
        .alert-success {
            background-color: #1c4a2d;
            border-color: #255c3a;
            color: #8cffb3;
        }
        
        table.table-dark {
            background-color: #2c2c3c;
        }
        
        table.table-dark th {
            background-color: #0d3b3f;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-7">
            <form method="post" action="checkout.php" class="form-container">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? $user['fullname'] ?? ''); ?>" required class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? $user['email'] ?? ''); ?>" required class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? $user['phone'] ?? ''); ?>" required class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="shipping_address" class="form-label">Shipping Address:</label>
                    <textarea id="shipping_address" name="shipping_address" rows="3" required class="form-control"><?php echo htmlspecialchars($_POST['shipping_address'] ?? $shipping_address_autofill ?? ''); ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="form-label">Payment Method:</label><br />
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" <?php if (($_POST['payment_method'] ?? '') === 'credit_card') echo 'checked'; ?> required />
                        <label class="form-check-label" for="credit_card">Credit Card</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" <?php if (($_POST['payment_method'] ?? '') === 'paypal') echo 'checked'; ?> />
                        <label class="form-check-label" for="paypal">PayPal</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" <?php if (($_POST['payment_method'] ?? '') === 'cod') echo 'checked'; ?> />
                        <label class="form-check-label" for="cod">Cash on Delivery</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Place Order</button>
            </form>
        </div>
        <div class="col-md-5">
            <h3 class="mb-3">Order Summary</h3>
            <div class="card">
                <div class="card-body">
                    <?php displayOrderSummary(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>