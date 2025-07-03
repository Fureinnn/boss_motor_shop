<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Only allow admin access
requireAdmin();

$pageTitle = "Manage Products";
include '../includes/header.php';

// Handle product actions
if(isset($_POST['add_product'])) {
    // Add new product
    try {
        $productName = $_POST['product_name'];
        $categoryId = $_POST['category_id'];
        $price = $_POST['price'];
        $stockQuantity = $_POST['stock_quantity'];
        $sku = $_POST['sku'];
        $imageUrl = $_POST['image_url'];
        $description = $_POST['description'];
        
        $stmt = $pdo->prepare("INSERT INTO PRODUCT (product_name, category_id, price, stock_quantity, sku, image_url, is_active) 
                              VALUES (?, ?, ?, ?, ?, ?, 1)");
        $stmt->execute([$productName, $categoryId, $price, $stockQuantity, $sku, $imageUrl]);
        
        $productId = $pdo->lastInsertId();
        
        // Add product info
        $stmt = $pdo->prepare("INSERT INTO PRODUCT_INFO (product_id, detailed_description) VALUES (?, ?)");
        $stmt->execute([$productId, $description]);
        
        $success = "Product added successfully!";
    } catch (PDOException $e) {
        $error = "Error adding product: " . $e->getMessage();
    }
} elseif(isset($_POST['update_product'])) {
    // Update existing product
    try {
        $productId = $_POST['product_id'];
        $productName = $_POST['product_name'];
        $categoryId = $_POST['category_id'];
        $price = $_POST['price'];
        $stockQuantity = $_POST['stock_quantity'];
        $sku = $_POST['sku'];
        $imageUrl = $_POST['image_url'];
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $description = $_POST['description'];
        
        $stmt = $pdo->prepare("UPDATE PRODUCT SET 
                              product_name = ?, 
                              category_id = ?, 
                              price = ?, 
                              stock_quantity = ?, 
                              sku = ?, 
                              image_url = ?, 
                              is_active = ? 
                              WHERE product_id = ?");
        $stmt->execute([$productName, $categoryId, $price, $stockQuantity, $sku, $imageUrl, $isActive, $productId]);
        
        // Update product info
        $stmt = $pdo->prepare("UPDATE PRODUCT_INFO SET detailed_description = ? WHERE product_id = ?");
        $stmt->execute([$description, $productId]);
        
        $success = "Product updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating product: " . $e->getMessage();
    }
} elseif(isset($_GET['delete'])) {
    // Delete product (soft delete)
    try {
        $productId = $_GET['delete'];
        
        $stmt = $pdo->prepare("UPDATE PRODUCT SET is_active = 0 WHERE product_id = ?");
        $stmt->execute([$productId]);
        
        $success = "Product deactivated successfully!";
    } catch (PDOException $e) {
        $error = "Error deactivating product: " . $e->getMessage();
    }
}

// Get product data for editing
$editProduct = null;
if(isset($_GET['edit'])) {
    try {
        $productId = $_GET['edit'];
        
        $stmt = $pdo->prepare("SELECT p.*, pi.detailed_description 
                              FROM PRODUCT p 
                              LEFT JOIN PRODUCT_INFO pi ON p.product_id = pi.product_id 
                              WHERE p.product_id = ?");
        $stmt->execute([$productId]);
        $editProduct = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Error loading product: " . $e->getMessage();
    }
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Products</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
            <i class="bi bi-plus"></i> Add Product
        </button>
    </div>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT p.*, c.category_name 
                                                FROM PRODUCT p 
                                                JOIN CATEGORY c ON p.category_id = c.category_id 
                                                ORDER BY p.product_id DESC");
                            while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $product['product_id'] . '</td>';
                                echo '<td><img src="' . htmlspecialchars($product['image_url']) . '" width="50" height="50" style="object-fit: cover;"></td>';
                                echo '<td>' . htmlspecialchars($product['product_name']) . '</td>';
                                echo '<td>' . htmlspecialchars($product['category_name']) . '</td>';
                                echo '<td>' . formatPrice($product['price']) . '</td>';
                                echo '<td>' . $product['stock_quantity'] . '</td>';
                                echo '<td><span class="badge bg-' . ($product['is_active'] ? 'success' : 'danger') . '">' . ($product['is_active'] ? 'Active' : 'Inactive') . '</span></td>';
                                echo '<td>';
                                echo '<a href="?edit=' . $product['product_id'] . '" class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>';
                                echo '<a href="?delete=' . $product['product_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="bi bi-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="8" class="text-center">Error loading products: ' . $e->getMessage() . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel"><?php echo $editProduct ? 'Edit' : 'Add'; ?> Product</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <?php if($editProduct): ?>
                        <input type="hidden" name="product_id" value="<?php echo $editProduct['product_id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" 
                               value="<?php echo $editProduct ? htmlspecialchars($editProduct['product_name']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM CATEGORY ORDER BY category_name");
                            while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($editProduct && $editProduct['category_id'] == $category['category_id']) ? 'selected' : '';
                                echo '<option value="' . $category['category_id'] . '" ' . $selected . '>' . htmlspecialchars($category['category_name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                   value="<?php echo $editProduct ? $editProduct['price'] : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                   value="<?php echo $editProduct ? $editProduct['stock_quantity'] : '0'; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" class="form-control" id="sku" name="sku" 
                                   value="<?php echo $editProduct ? htmlspecialchars($editProduct['sku']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image_url" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="image_url" name="image_url" 
                                   value="<?php echo $editProduct ? htmlspecialchars($editProduct['image_url']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?php 
                            echo $editProduct ? htmlspecialchars($editProduct['detailed_description']) : ''; 
                        ?></textarea>
                    </div>
                    
                    <?php if($editProduct): ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   <?php echo $editProduct['is_active'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="<?php echo $editProduct ? 'update_product' : 'add_product'; ?>" class="btn btn-primary">
                        <?php echo $editProduct ? 'Update' : 'Add'; ?> Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($editProduct): ?>
<script>
    // Show modal if editing
    document.addEventListener('DOMContentLoaded', function() {
        var productModal = new bootstrap.Modal(document.getElementById('productModal'));
        productModal.show();
    });
</script>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>