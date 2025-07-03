<?php
session_start();
require_once 'includes/config.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch fresh user data from database
$stmt = $mysqli->prepare("SELECT fullname, gender, dob, phone, email, street, city, state, zip, country, username, created_at FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile - Boss's Motor Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <?php include 'includes/header.php'; ?>

  <!-- Main Content -->
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <!-- Welcome Banner -->
        <div class="profile-header p-5 mb-5">
          <div class="row align-items-center">
            <div class="col-md-2 text-center text-md-start mb-4 mb-md-0">
              <div class="avatar">
                <i class="bi bi-person-gear"></i>
              </div>
            </div>
            <div class="col-md-7 text-center text-md-start">
              <h1 class="display-5 fw-bold mb-2">Welcome back, <?php echo htmlspecialchars($user['fullname']); ?>!</h1>
              <p class="lead mb-0">Your premium motorcycle service experience since <?php echo date('F Y', strtotime($user['created_at'])); ?></p>
            </div>
            <div class="col-md-3 text-center text-md-end mt-4 mt-md-0">
              <a href="#" class="btn btn-outline-light btn-lg">
                <i class="bi bi-pencil-fill me-2"></i>Edit Profile
              </a>
            </div>
          </div>
        </div>

        <div class="row g-4">
          <!-- Personal Information Card -->
          <div class="col-lg-6">
            <div class="card h-100">
              <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Personal Information</h3>
              </div>
              <div class="card-body">
                <div class="profile-detail">
                  <i class="bi bi-person"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Full Name</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['fullname']); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-envelope"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Email Address</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['email']); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-telephone"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Phone Number</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['phone']); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-calendar"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Date of Birth</h6>
                    <p class="mb-0"><?php echo date('F j, Y', strtotime($user['dob'])); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Address Information Card -->
          <div class="col-lg-6">
            <div class="card h-100">
              <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-house-door me-2"></i>Address Information</h3>
              </div>
              <div class="card-body">
                <div class="profile-detail">
                  <i class="bi bi-geo-alt"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Street</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['street']); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-building"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">City</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['city']); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-map"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">State/Province</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['state']); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-postcard"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Zip Code & Country</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['zip']); ?>, <?php echo htmlspecialchars($user['country']); ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Account Information Card -->
          <div class="col-lg-6">
            <div class="card h-100">
              <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Account Information</h3>
              </div>
              <div class="card-body">
                <div class="profile-detail">
                  <i class="bi bi-person-badge"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Username</h6>
                    <p class="mb-0"><?php echo htmlspecialchars($user['username']); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-calendar-check"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Member Since</h6>
                    <p class="mb-0"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-star"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Account Status</h6>
                    <span class="badge bg-success">Active</span>
                  </div>
                </div>
                <div class="profile-detail">
                  <i class="bi bi-key"></i>
                  <div class="ms-3">
                    <h6 class="mb-0">Password</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">Change Password</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions Card -->
          <div class="col-lg-6">
            <div class="card h-100">
              <div class="card-header">
                <h3 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h3>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-6">
                    <a href="#" class="btn btn-outline-primary action-btn">
                      <i class="bi bi-calendar-check me-2"></i>Book Service
                    </a>
                  </div>
                  <div class="col-md-6">
                    <a href="#" class="btn btn-outline-primary action-btn">
                      <i class="bi bi-clock-history me-2"></i>My Appointments
                    </a>
                  </div>
                  <div class="col-md-6">
                    <a href="#" class="btn btn-outline-primary action-btn">
                      <i class="bi bi-credit-card me-2"></i>Payment Methods
                    </a>
                  </div>
                  <div class="col-md-6">
<a href="logout.php" class="btn btn-logout action-btn">
  <i class="bi bi-box-arrow-right me-2"></i>Log Out
</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>