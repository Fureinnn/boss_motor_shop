<?php
session_start();
$pageTitle = "Thank You - Boss's Motor Shop";
include 'includes/header.php';

$order_number = $_GET['order'] ?? '';

if ($order_number) {
    require_once 'includes/config.php';

    // Fetch order details
    $stmt_order = $mysqli->prepare("SELECT order_id, user_id, total_amount, tax_amount, shipping_cost, order_status, payment_status, payment_method, shipping_address, order_date FROM `order` WHERE order_number = ?");
    $stmt_order->bind_param("s", $order_number);
    $stmt_order->execute();
    $order_result = $stmt_order->get_result();
    $order = $order_result->fetch_assoc();
    $stmt_order->close();

    // Fetch order items
    $order_items = [];
    if ($order) {
        $stmt_items = $mysqli->prepare("SELECT p.product_name, oi.quantity, oi.unit_price FROM order_items oi JOIN product p ON oi.product_id = p.product_id WHERE oi.order_id = ?");
        $stmt_items->bind_param("i", $order['order_id']);
        $stmt_items->execute();
        $result_items = $stmt_items->get_result();
        $order_items = $result_items->fetch_all(MYSQLI_ASSOC);
        $stmt_items->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-8 text-center">
        <h1>Thank You for Your Order!</h1>
        <?php if ($order_number): ?>
          <p>Your order number is: <strong><?php echo htmlspecialchars($order_number); ?></strong></p>
          <?php if ($order): ?>
            <p>Order Date: <?php echo htmlspecialchars($order['order_date']); ?></p>
            <p>Payment Method: <?php echo htmlspecialchars($order['payment_method']); ?></p>
            <p>Order Status: <?php echo htmlspecialchars($order['order_status']); ?></p>
            <p>Payment Status: <?php echo htmlspecialchars($order['payment_status']); ?></p>
            <p>Shipping Address: <?php echo htmlspecialchars($order['shipping_address']); ?></p>
            <h3>Purchased Products:</h3>
            <?php if (!empty($order_items)): ?>
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($order_items as $item): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                      <td><?php echo intval($item['quantity']); ?></td>
                      <td><?php echo number_format($item['unit_price'], 2); ?></td>
                      <td><?php echo number_format($item['unit_price'] * $item['quantity'], 2); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p>No products found for this order.</p>
            <?php endif; ?>
          <?php else: ?>
            <p>Order details not found.</p>
          <?php endif; ?>
        <?php else: ?>
          <p>Your order has been received.</p>
        <?php endif; ?>
        <p>We appreciate your business and will process your order shortly.</p>
        <a href="index.php" class="btn btn-primary mt-3">Return to Home</a>
      </div>
    </div>
  </div>
  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
