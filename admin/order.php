<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Only allow admin access
requireAdmin();

$pageTitle = "Manage Orders";
include '../includes/header.php';

// Handle order status update
if(isset($_POST['update_status'])) {
    try {
        $orderId = $_POST['order_id'];
        $status = $_POST['status'];
        
        $stmt = $pdo->prepare("UPDATE `ORDER` SET order_status = ? WHERE order_id = ?");
        $stmt->execute([$status, $orderId]);
        
        $success = "Order status updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating order: " . $e->getMessage();
    }
}

// Get order details if viewing specific order
$orderDetails = null;
if(isset($_GET['view'])) {
    try {
        $orderId = $_GET['view'];
        
        // Get order info
        $stmt = $pdo->prepare("SELECT o.*, u.username, p.first_name, p.last_name 
                              FROM `ORDER` o 
                              JOIN USER u ON o.user_id = u.user_id 
                              LEFT JOIN PROFILE p ON u.user_id = p.user_id 
                              WHERE o.order_id = ?");
        $stmt->execute([$orderId]);
        $orderDetails = $stmt->fetch();
        
        if($orderDetails) {
            // Get order items
            $stmt = $pdo->prepare("SELECT oi.*, p.product_name, p.image_url 
                                  FROM ORDER_ITEM oi 
                                  JOIN PRODUCT p ON oi.product_id = p.product_id 
                                  WHERE oi.order_id = ?");
            $stmt->execute([$orderId]);
            $orderItems = $stmt->fetchAll();
        }
    } catch (PDOException $e) {
        $error = "Error loading order: " . $e->getMessage();
    }
}
?>

<div class="container py-5">
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if($orderDetails): ?>
        <!-- Order Details View -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Order #<?php echo htmlspecialchars($orderDetails['order_number']); ?></h2>
            <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Order Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-dark">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orderItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" width="50" height="50" class="me-3" style="object-fit: cover;">
                                                    <div><?php echo htmlspecialchars($item['product_name']); ?></div>
                                                </div>
                                            </td>
                                            <td><?php echo formatPrice($item['unit_price']); ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td><?php echo formatPrice($item['total_price']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Subtotal:</th>
                                        <th><?php echo formatPrice($orderDetails['total_amount']); ?></th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Shipping:</th>
                                        <th>₱0.00</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th><?php echo formatPrice($orderDetails['total_amount']); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Order Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($orderDetails['order_date'])); ?></p>
                        <p><strong>Customer:</strong> <?php 
                            echo htmlspecialchars($orderDetails['first_name'] . ' ' . $orderDetails['last_name']) ?: 
                            htmlspecialchars($orderDetails['username']); 
                        ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-<?php 
                                echo $orderDetails['order_status'] == 'completed' ? 'success' : 
                                    ($orderDetails['order_status'] == 'cancelled' ? 'danger' : 'warning'); 
                            ?>">
                                <?php echo ucfirst($orderDetails['order_status']); ?>
                            </span>
                        </p>
                        <p><strong>Payment Status:</strong> 
                            <span class="badge bg-<?php 
                                echo $orderDetails['payment_status'] == 'paid' ? 'success' : 
                                    ($orderDetails['payment_status'] == 'failed' ? 'danger' : 'warning'); 
                            ?>">
                                <?php echo ucfirst($orderDetails['payment_status']); ?>
                            </span>
                        </p>
                        
                        <hr>
                        
                        <form method="post">
                            <input type="hidden" name="order_id" value="<?php echo $orderDetails['order_id']; ?>">
                            <div class="mb-3">
                                <label for="status" class="form-label">Update Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" <?php echo $orderDetails['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $orderDetails['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $orderDetails['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="completed" <?php echo $orderDetails['order_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo $orderDetails['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Orders List View -->
        <h2 class="mb-4">Manage Orders</h2>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                $stmt = $pdo->query("SELECT o.*, u.username 
                                                    FROM `ORDER` o 
                                                    JOIN USER u ON o.user_id = u.user_id 
                                                    ORDER BY o.order_date DESC");
                                while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($order['order_number']) . '</td>';
                                    echo '<td>' . date('M d, Y', strtotime($order['order_date'])) . '</td>';
                                    echo '<td>' . htmlspecialchars($order['username']) . '</td>';
                                    echo '<td>' . formatPrice($order['total_amount']) . '</td>';
                                    echo '<td><span class="badge bg-' . ($order['order_status'] == 'completed' ? 'success' : 
                                                                        ($order['order_status'] == 'cancelled' ? 'danger' : 'warning')) . '">' . 
                                         ucfirst($order['order_status']) . '</span></td>';
                                    echo '<td><span class="badge bg-' . ($order['payment_status'] == 'paid' ? 'success' : 
                                                                        ($order['payment_status'] == 'failed' ? 'danger' : 'warning')) . '">' . 
                                         ucfirst($order['payment_status']) . '</span></td>';
                                    echo '<td><a href="?view=' . $order['order_id'] . '" class="btn btn-sm btn-primary">View</a></td>';
                                    echo '</tr>';
                                }
                            } catch (PDOException $e) {
                                echo '<tr><td colspan="7" class="text-center">Error loading orders: ' . $e->getMessage() . '</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>