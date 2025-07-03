<?php
$pageTitle = "About Us - Boss's Motor Shop";
include 'includes/header.php';

$contact_success = false;
$contact_errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    // Sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validate inputs
    if (empty($name)) {
        $contact_errors['name'] = "Please enter your name.";
    }
    if (empty($email)) {
        $contact_errors['email'] = "Please enter your email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contact_errors['email'] = "Please enter a valid email address.";
    }
    if (empty($message)) {
        $contact_errors['message'] = "Please enter your message.";
    }

    // If no errors, process the contact form (e.g., send email or store in DB)
    if (empty($contact_errors)) {
        // For demonstration, we just set success flag
        $contact_success = true;

        // TODO: Implement email sending or database storage here

        // Clear form fields
        $name = $email = $phone = $message = '';
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
  </div>

  <div class="container py-5">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <h2 class="text-center mb-5 about-title">About Boss's Motor Shop</h2>

        <p class="text-center mb-3" style="color: #ffffff;">
          Wanna learn more about us? Click these links: <br />
          <a href="https://refugio-portfolio.tiiny.site/#home" target="_blank" rel="noopener noreferrer" style="color: #0d6efd;">Refugio_portfolio</a><br />
          <a href="https://cip1101soriao2024-2025.on.drv.tw/www.cip1101SORIAO/Renzo%20Integ%20Portfolio/Rodriguez_Portfolio.html?fbclid=IwY2xjawLTG85leHRuA2FlbQIxMABicmlkETB1Z0tFeWhHbWh2ak02VUhWAR7sLvAToxtt0wU1FteYg_G1X42FWslWu5_ec8T-zynlp7_VO7B1dyLnKngq1A_aem_53zjQs0jV2gdH_aftX_8Lg" target="_blank" rel="noopener noreferrer" style="color: #0d6efd;">Rodriguez_Portfolio</a>
        </p>

        <!-- Contact Section -->
        <div class="card mb-4 about-contact-form">
          <div class="card-header">
            <h4 class="card-title"><i class="bi bi-envelope me-2"></i>Contact Us</h4>
          </div>
          <div class="card-body">
            <?php if ($contact_success): ?>
              <div class="alert alert-success" role="alert">Thank you for contacting us! We will get back to you shortly.</div>
            <?php endif; ?>
            <form method="POST" action="about.php" novalidate>
              <div class="mb-3" style="--i: 1;">
                <label for="name" class="form-label" style="color: #ffffff;">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control <?php echo isset($contact_errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required aria-describedby="nameError">
                <div id="nameError" class="invalid-feedback"><?php echo $contact_errors['name'] ?? ''; ?></div>
              </div>
              <div class="mb-3" style="--i: 2;">
                <label for="email" class="form-label" style="color: #ffffff;">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control <?php echo isset($contact_errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required aria-describedby="emailError">
                <div id="emailError" class="invalid-feedback"><?php echo $contact_errors['email'] ?? ''; ?></div>
              </div>
              <div class="mb-3" style="--i: 3;">
                <label for="phone" class="form-label" style="color: #ffffff;">Phone (Optional)</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" aria-describedby="phoneLabel">
              </div>
              <div class="mb-3" style="--i: 4;">
                <label for="message" class="form-label" style="color: #ffffff;">Message <span class="text-danger">*</span></label>
                <textarea class="form-control <?php echo isset($contact_errors['message']) ? 'is-invalid' : ''; ?>" id="message" name="message" rows="4" required aria-describedby="messageError"><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                <div id="messageError" class="invalid-feedback"><?php echo $contact_errors['message'] ?? ''; ?></div>
              </div>
              <button type="submit" name="contact_submit" class="btn btn-primary action-btn">Send Message</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    window.addEventListener('load', () => {
      const loadingOverlay = document.getElementById('loadingOverlay');
      setTimeout(() => {
        loadingOverlay.classList.add('hidden');
      }, 500);
    });
  </script>
</body>
</html>
