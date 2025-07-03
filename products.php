<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = "Products - Boss's Motor Shop";
include 'includes/header.php';

// Handle category filter
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$searchQuery = isset($_GET['search']) ? sanitize($_GET['search']) : '';
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Our Products</h2>
        </div>
        <div class="col-md-6">
            <form method="get" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search products..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4 categories-card">
                <div class="card-header">
                    <h5 style="color: #00d4ff;">Categories</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php
                        $stmt = executeQuery($mysqli, "SELECT * FROM category ORDER BY category_name");
                        $result = $stmt->get_result();
                        echo '<li class="mb-2"><a href="products.php" class="text-white' . ($categoryFilter == 0 ? ' active-category' : '') . '">All Categories</a></li>';
                        while ($category = $result->fetch_assoc()) {
                            $active = ($categoryFilter == $category['category_id']) ? ' active-category' : '';
                            echo '<li class="mb-2"><a href="products.php?category=' . $category['category_id'] . '" class="text-white' . $active . '">' . htmlspecialchars($category['category_name']) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row g-4">
                <?php
                $sql = "SELECT p.product_id, p.product_name, p.price, p.image_url, p.stock_quantity, c.category_name 
                       FROM product p 
                       JOIN category c ON p.category_id = c.category_id 
                       WHERE p.is_active = 1";
                
                $params = [];
                $types = '';
                
                if ($categoryFilter > 0) {
                    $sql .= " AND p.category_id = ?";
                    $params[] = $categoryFilter;
                    $types .= 'i';
                }
                
                if (!empty($searchQuery)) {
                    $sql .= " AND (p.product_name LIKE ? OR p.sku LIKE ?)";
                    $params[] = "%$searchQuery%";
                    $params[] = "%$searchQuery%";
                    $types .= 'ss';
                }
                
                $sql .= " ORDER BY p.product_name";
                
                $stmt = executeQuery($mysqli, $sql, $params, $types);
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    while ($product = $result->fetch_assoc()) {
                        echo '<div class="col-md-4">';
                        echo '<div class="card product-card h-100">';
                        echo '<img src="' . htmlspecialchars($product['image_url']) . '" class="card-img-top product-img" alt="' . htmlspecialchars($product['product_name']) . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title" style="color: #00d4ff;"><a href="productinfo.php?product_id=' . $product['product_id'] . '" style="color: #00d4ff; text-decoration: none;">' . htmlspecialchars($product['product_name']) . '</a></h5>';
                        echo '<p class="card-text products-text-color">' . formatPrice($product['price']) . '</p>';
                        echo '<p class="card-text products-text-color"><small class="text-white">' . htmlspecialchars($product['category_name']) . '</small></p>';
                                                
                        if ($product['stock_quantity'] > 0) {
                            echo '<p class="text-success">In Stock (' . $product['stock_quantity'] . ')</p>';
                        } else {
                            echo '<p class="text-danger">Out of Stock</p>';
                        }
                        
                        if (isLoggedIn()) {
                            echo '<form method="post" action="cart.php">';
                            echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
                            echo '<div class="input-group mb-3">';
                            echo '<input type="number" name="quantity" class="form-control" value="1" min="1" max="' . $product['stock_quantity'] . '">';
                            echo '<button type="submit" name="add_to_cart" class="btn btn-primary" ' . ($product['stock_quantity'] <= 0 ? 'disabled' : '') . '>Add to Cart</button>';
                            echo '</div></form>';
                        } else {
                            echo '<a href="login.php" class="btn btn-primary">Login to Purchase</a>';
                        }
                        
                        echo '</div></div></div>';
                    }
                } else {
                    echo '<div class="col-12"><div class="alert alert-info">No products found matching your criteria.</div></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>