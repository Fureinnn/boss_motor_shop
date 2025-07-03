<?php
session_start();
$pageTitle = "Your Cart - Boss's Motor Shop";
include 'includes/header.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle add to cart
if(isset($_POST['add_to_cart'])) {
    try {
        require_once 'includes/config.php';
        
        $productId = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $userId = $_SESSION['user_id'];
        
        // Check if product exists and is in stock
        $stmt = $mysqli->prepare("SELECT * FROM PRODUCT WHERE product_id = ? AND is_active = 1 AND stock_quantity >= ?");
        $stmt->bind_param("ii", $productId, $quantity);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if($product) {
            // Check if user has an active cart
            $stmt = $mysqli->prepare("SELECT * FROM CART WHERE user_id = ? AND status = 'active'");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $cart = $result->fetch_assoc();
            
            if(!$cart) {
                // Create new cart
                $stmt = $mysqli->prepare("INSERT INTO CART (user_id, status) VALUES (?, 'active')");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $cartId = $mysqli->insert_id;
            } else {
                $cartId = $cart['cart_id'];
            }
            
            // Check if product is already in cart
            $stmt = $mysqli->prepare("SELECT * FROM CART_ITEM WHERE cart_id = ? AND product_id = ?");
            $stmt->bind_param("ii", $cartId, $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            $existingItem = $result->fetch_assoc();
            
            if($existingItem) {
                // Update quantity
                $newQuantity = $existingItem['quantity'] + $quantity;
                $stmt = $mysqli->prepare("UPDATE CART_ITEM SET quantity = ?, total_price = ? * unit_price WHERE cart_item_id = ?");
                $stmt->bind_param("idi", $newQuantity, $newQuantity, $existingItem['cart_item_id']);
                $stmt->execute();
            } else {
                // Add new item
                $stmt = $mysqli->prepare("INSERT INTO CART_ITEM (cart_id, product_id, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
                $totalPrice = $product['price'] * $quantity;
                $stmt->bind_param("iiidd", $cartId, $productId, $quantity, $product['price'], $totalPrice);
                $stmt->execute();
            }
            
            $success = "Product added to cart successfully!";
        } else {
            $error = "Product not available or insufficient stock";
        }
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Handle remove from cart
if(isset($_GET['remove'])) {
    try {
        require_once 'includes/config.php';
        
        $cartItemId = $_GET['remove'];
        $userId = $_SESSION['user_id'];
        
        // Verify the cart item belongs to the user
        $stmt = $mysqli->prepare("DELETE ci FROM CART_ITEM ci JOIN CART c ON ci.cart_id = c.cart_id WHERE ci.cart_item_id = ? AND c.user_id = ?");
        $stmt->bind_param("ii", $cartItemId, $userId);
        $stmt->execute();
        
        if($stmt->affected_rows > 0) {
            $success = "Item removed from cart";
        } else {
            $error = "Item not found in your cart";
        }
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Handle update quantity
if(isset($_POST['update_quantity'])) {
    try {
        require_once 'includes/config.php';
        
        $cartItemId = $_POST['cart_item_id'];
        $quantity = (int)$_POST['quantity'];
        $userId = $_SESSION['user_id'];
        
        // Verify the cart item belongs to the user and check stock
        $stmt = $mysqli->prepare("SELECT ci.*, p.stock_quantity 
                              FROM CART_ITEM ci 
                              JOIN CART c ON ci.cart_id = c.cart_id 
                              JOIN PRODUCT p ON ci.product_id = p.product_id 
                              WHERE ci.cart_item_id = ? AND c.user_id = ?");
        $stmt->bind_param("ii", $cartItemId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();
        
        if($item) {
            if($quantity <= 0) {
                // Remove item if quantity is 0 or less
                $stmt = $mysqli->prepare("DELETE FROM CART_ITEM WHERE cart_item_id = ?");
                $stmt->bind_param("i", $cartItemId);
                $stmt->execute();
                $success = "Item removed from cart";
            } elseif($quantity <= $item['stock_quantity']) {
                // Update quantity
                $stmt = $mysqli->prepare("UPDATE CART_ITEM SET quantity = ?, total_price = ? * unit_price WHERE cart_item_id = ?");
                $stmt->bind_param("idi", $quantity, $quantity, $cartItemId);
                $stmt->execute();
                $success = "Cart updated successfully";
            } else {
                $error = "Insufficient stock for this product";
            }
        } else {
            $error = "Item not found in your cart";
        }
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}


?>

<div class="container py-5">
    <h2 class="mb-4">Your Shopping Cart</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-8">
            <?php
            try {
                require_once 'includes/config.php';
                
                $userId = $_SESSION['user_id'];
                
                // Get user's active cart with items
                $stmt = $mysqli->prepare("SELECT ci.*, p.product_name, p.image_url, p.stock_quantity 
                                      FROM CART c 
                                      JOIN CART_ITEM ci ON c.cart_id = ci.cart_id 
                                      JOIN PRODUCT p ON ci.product_id = p.product_id 
                                      WHERE c.user_id = ? AND c.status = 'active'");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $cartItems = $result->fetch_all(MYSQLI_ASSOC);
                
                if(count($cartItems) > 0) {
                    foreach($cartItems as $item) {
                        echo '<div class="card mb-3">';
                        echo '<div class="row g-0">';
                        echo '<div class="col-md-3">';
                        echo '<img src="' . htmlspecialchars($item['image_url']) . '" class="img-fluid rounded-start product-img" alt="' . htmlspecialchars($item['product_name']) . '">';
                        echo '</div>';
                        echo '<div class="col-md-7">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title" style= "color: #00d4ff">' . htmlspecialchars($item['product_name']) . '</h5>';
                        echo '<p class="card-text">₱' . number_format($item['unit_price'], 2) . ' each</p>';
                        echo '<p class="card-text"><small class="text-success">Available: ' . $item['stock_quantity'] . '</small></p>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="col-md-2">';
                        echo '<div class="card-body">';
                        echo '<form method="post" action="cart.php">';
                        echo '<input type="hidden" name="cart_item_id" value="' . $item['cart_item_id'] . '">';
                        echo '<div class="input-group mb-2">';
                        echo '<input type="number" name="quantity" class="form-control" value="' . $item['quantity'] . '" min="1" max="' . $item['stock_quantity'] . '">';
                        echo '<button type="submit" name="update_quantity" class="btn btn-sm btn-primary">Update</button>';
                        echo '</div>';
                        echo '</form>';
                        echo '<p class="card-text fw-bold">₱' . number_format($item['total_price'], 2) . '</p>';
                        echo '<a href="cart.php?remove=' . $item['cart_item_id'] . '" class="btn btn-sm btn-danger">Remove</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="alert alert-info">Your cart is empty. <a href="products.php" class="alert-link">Browse products</a></div>';
                }
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error loading cart: ' . $e->getMessage() . '</div>';
            }
            ?>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 style="color: #00d4ff">Order Summary</h5>
                </div>
                <div class="card-body">
                    <?php
                    try {
                        $userId = $_SESSION['user_id'];
                        
                        // Calculate cart total
                        $stmt = $mysqli->prepare("SELECT SUM(ci.total_price) as total 
                                              FROM CART c 
                                              JOIN CART_ITEM ci ON c.cart_id = ci.cart_id 
                                              WHERE c.user_id = ? AND c.status = 'active'");
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $cartTotal = $result->fetch_assoc()['total'];
                        
                        if($cartTotal) {
                            echo '<p class="d-flex justify-content-between"><span>Subtotal:</span> <span>₱' . number_format($cartTotal, 2) . '</span></p>';
                            echo '<p class="d-flex justify-content-between"><span>Shipping:</span> <span>₱0.00</span></p>';
                            echo '<hr>';
                            echo '<h5 class="d-flex justify-content-between" style="color: #ffffff"><span>Total:</span> <span>₱' . number_format($cartTotal, 2) . '</span></h5>';
                            
if(count($cartItems) > 0) {
    echo '</form>'; // Close any open form before the link
    echo '<a href="checkout.php" class="btn btn-primary w-100 mt-3 d-block text-center">Proceed to Checkout</a>';
}
                        } else {
                            echo '<p>No items in cart</p>';
                        }
                    } catch (Exception $e) {
                        echo '<div class="alert alert-danger">Error calculating total: ' . $e->getMessage() . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
