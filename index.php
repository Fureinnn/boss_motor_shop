<?php
$pageTitle = "Home - Boss's Motor Shop";
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section text-center text-white">
  <div class="container">
    <h1 class="display-4 fw-bold hero-title">Premium Motorcycle Services</h1>
    <p class="lead">Experience the best in motorcycle maintenance, repairs, and customization</p>
    <div class="mt-4">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="products.php" class="btn btn-primary btn-lg me-2">Shop Now</a>
        <a href="products.php" class="btn btn-outline-light btn-lg">View Products</a>
      <?php else: ?>
        <a href="register.php" class="btn btn-primary btn-lg me-2">Sign Up</a>
        <a href="login.php" class="btn btn-outline-light btn-lg">Login</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Services Section -->
<section class="container my-5">
  <div class="text-center mb-5">
    <h2>Our Services</h2>
    <p class="lead">Comprehensive solutions for all your motorcycle needs</p>
  </div>
  
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="service-icon">
            <i class="bi bi-tools"></i>
          </div>
          <h4>General Repairs</h4>
          <p>From minor fixes to major overhauls, our expert technicians handle all types of motorcycle repairs.</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="service-icon">
            <i class="bi bi-speedometer2"></i>
          </div>
          <h4>Performance Tuning</h4>
          <p>Maximize your bike's potential with our professional engine tuning and performance upgrades.</p>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body text-center">
          <div class="service-icon">
            <i class="bi bi-palette"></i>
          </div>
          <h4>Customization</h4>
          <p>Make your bike uniquely yours with our customization services from paint jobs to aftermarket parts.</p>
        </div>
      </div>
    </div>
  </div>
  
  <div class="text-center mt-4">
    <a href="products.php" class="btn btn-outline-primary">View All Products</a>
  </div>
</section>

<!-- Featured Products Section -->
<section class="container my-5">
  <h2 class="mb-4 text-center">Featured Products</h2>
  <div class="row g-4">
    <?php
    try {
      require_once 'includes/config.php';

      $sql = "SELECT p.product_id, p.product_name, p.price, p.image_url, c.category_name 
              FROM PRODUCT p 
              JOIN CATEGORY c ON p.category_id = c.category_id 
              WHERE p.is_active = 1 
              ORDER BY RAND() LIMIT 4";

      if ($result = $mysqli->query($sql)) {
          while ($product = $result->fetch_assoc()) {
              echo '<div class="col-md-3">';
              echo '<div class="card product-card">';
              echo '<img src="' . htmlspecialchars($product['image_url']) . '" class="card-img-top product-img" alt="' . htmlspecialchars($product['product_name']) . '">';
              echo '<div class="card-body text-center">';
              echo '<h5 class="product-title mt-2 white text-info">' . htmlspecialchars($product['product_name']) . '</h5>';
              echo '<p class="text-white">₱' . number_format($product['price'], 2) . '</p>';
              echo '<small class="text-white">' . htmlspecialchars($product['category_name']) . '</small><br>';

              if(isset($_SESSION['user_id'])) {
                  echo '<form method="post" action="cart.php">';
                  echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
                  echo '<button type="submit" name="add_to_cart" class="btn btn-sm btn-primary mt-2">Add to Cart</button>';
                  echo '</form>';
              } else {
                  echo '<a href="login.php" class="btn btn-sm btn-primary mt-2">Login to Purchase</a>';
              }

              echo '</div></div></div>';
          }
          $result->free();
      } else {
          echo '<div class="col-12 text-center"><p>Error loading products: ' . $mysqli->error . '</p></div>';
      }
    } catch (Exception $e) {
      echo '<div class="col-12 text-center"><p>Error loading products: ' . $e->getMessage() . '</p></div>';
    }
    ?>
  </div>
  <div class="text-center mt-4">
    <a href="products.php" class="btn btn-outline-primary">View All Products</a>
  </div>
</section>

<!-- Testimonials -->
<section class="container my-5">
  <div class="text-center mb-5">
    <h2>What Our Customers Say</h2>
  </div>
  
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="mb-3 text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
          </div>
          <p>"Boss's Motor Shop brought my old Harley back to life. Their attention to detail is unmatched!"</p>
          <div class="d-flex align-items-center">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle me-3" width="50" alt="Customer">
            <div>
              <h6 class="mb-0">Mike Johnson</h6>
              <small class="text-white">Harley Davidson Owner</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="mb-3 text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
          </div>
          <p>"The performance tuning transformed my bike. It's like riding a completely different machine now!"</p>
          <div class="d-flex align-items-center">
            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle me-3" width="50" alt="Customer">
            <div>
              <h6 class="mb-0">Sarah Williams</h6>
              <small class="text-white">Sportbike Enthusiast</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="mb-3 text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-half"></i>
          </div>
          <p>"Honest mechanics who don't try to upsell unnecessary services. I trust them with all my bikes."</p>
          <div class="d-flex align-items-center">
            <img src="https://randomuser.me/api/portraits/men/75.jpg" class="rounded-circle me-3" width="50" alt="Customer">
            <div>
              <h6 class="mb-0">David Chen</h6>
              <small class="text-white">Long-time Customer</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action -->
<section class="py-5 text-center" style="background-color: #2c2c3c;">
  <div class="container">
    <h2>Ready to Experience the Difference?</h2>
    <p class="lead mb-4">Schedule your service appointment today and join our family of satisfied riders.</p>
    <a href="products.php" class="btn btn-primary btn-lg me-3">Shop Now</a>
    <a href="tel:+1234567890" class="btn btn-outline-light btn-lg"><i class="bi bi-telephone"></i> Call Us</a>
  </div>
</section>

<?php include 'includes/footer.php'; ?>