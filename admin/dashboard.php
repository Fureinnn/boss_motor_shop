<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Only allow admin access
requireAdmin();

$pageTitle = "Admin Dashboard";
include '../includes/header.php';
?>

<div class="container py-5">
    <h2 class="mb-4">Admin Dashboard</h2>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM PRODUCT");
                    $count = $stmt->fetchColumn();
                    ?>
                    <h3><?php echo $count; ?></h3>
                    <a href="products.php" class="btn btn-sm btn-primary">Manage Products</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM `ORDER`");
                    $count = $stmt->fetchColumn();
                    ?>
                    <h3><?php echo $count; ?></h3>
                    <a href="orders.php" class="btn btn-sm btn-primary">View Orders</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) FROM USER");
                    $count = $stmt->fetchColumn();
                    ?>
                    <h3><?php echo $count; ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5>Recent Orders</h5>
        </div>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT o.order_id, o.order_number, o.order_date, o.total_amount, o.order_status, u.username 
                                            FROM `ORDER` o 
                                            JOIN USER u ON o.user_id = u.user_id 
                                            ORDER BY o.order_date DESC LIMIT 5");
                        while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($order['order_number']) . '</td>';
                            echo '<td>' . date('M d, Y', strtotime($order['order_date'])) . '</td>';
                            echo '<td>' . htmlspecialchars($order['username']) . '</td>';
                            echo '<td>' . formatPrice($order['total_amount']) . '</td>';
                            echo '<td><span class="badge bg-' . ($order['order_status'] == 'completed' ? 'success' : 'warning') . '">' . ucfirst($order['order_status']) . '</span></td>';
                            echo '<td><a href="orders.php?view=' . $order['order_id'] . '" class="btn btn-sm btn-primary">View</a></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>