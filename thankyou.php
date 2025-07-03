<?php
session_start();
$pageTitle = "Thank You - Boss's Motor Shop";
include 'includes/header.php';

$order_number = $_GET['order'] ?? '';

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
