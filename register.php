<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$fullname = $gender = $dob = $phone = $email = $street = $city = $state = $zip = $country = $username = '';
$errors = [];
$registration_success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate full name
    if (empty($_POST["fullname"])) {
        $errors['fullname'] = "Please enter your full name.";
    } else {
        $fullname = sanitize($_POST["fullname"]);
        if (!preg_match("/^[a-zA-Z\s.,]{2,50}$/", $fullname)) {
            $errors['fullname'] = "Name must be 2-50 characters (letters, spaces, commas, or periods only).";
        }
    }

    // Validate gender
    if (empty($_POST["gender"])) {
        $errors['gender'] = "Please select your gender.";
    } else {
        $gender = sanitize($_POST["gender"]);
    }

    // Validate date of birth
    if (empty($_POST["dob"])) {
        $errors['dob'] = "Please enter your date of birth.";
    } else {
        $dob = $_POST["dob"];
        $today = new DateTime();
        $birthdate = DateTime::createFromFormat('Y-m-d', $dob);
        if (!$birthdate) {
            $errors['dob'] = "Invalid date format.";
        } else {
            $age = $today->diff($birthdate)->y;
            if ($age < 18) {
                $errors['dob'] = "You must be at least 18 years old.";
            }
        }
    }

    // Validate phone
    if (empty($_POST["phone"])) {
        $errors['phone'] = "Please enter your phone number.";
    } else {
        $phone = sanitize($_POST["phone"]);
        if (!preg_match("/^09\d{9}$/", $phone)) {
            $errors['phone'] = "Phone must be 11 digits starting with 09.";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $errors['email'] = "Please enter your email address.";
    } else {
        $email = sanitize($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address.";
        }
    }

    // Validate street
    if (empty($_POST["street"])) {
        $errors['street'] = "Please enter your street address.";
    } else {
        $street = sanitize($_POST["street"]);
        if (!preg_match("/^[a-zA-Z0-9\s.,#-]{5,100}$/", $street)) {
            $errors['street'] = "Street must be 5-100 characters (letters, numbers, spaces, .,-,#)";
        }
    }

    // Validate city
    if (empty($_POST["city"])) {
        $errors['city'] = "Please enter your city.";
    } else {
        $city = sanitize($_POST["city"]);
        if (!preg_match("/^[a-zA-Z\s]{2,50}$/", $city)) {
            $errors['city'] = "City must be 2-50 letters and spaces only.";
        }
    }

    // Validate state/province
    if (empty($_POST["state"])) {
        $errors['state'] = "Please enter your province/state.";
    } else {
        $state = sanitize($_POST["state"]);
        if (!preg_match("/^[a-zA-Z\s]{2,50}$/", $state)) {
            $errors['state'] = "State/Province must be 2-50 letters and spaces only.";
        }
    }

    // Validate zip code
    if (empty($_POST["zip"])) {
        $errors['zip'] = "Please enter your zip code.";
    } else {
        $zip = sanitize($_POST["zip"]);
        if (!preg_match("/^\d{4}$/", $zip)) {
            $errors['zip'] = "Zip code must be exactly 4 digits.";
        }
    }

    // Validate country
    if (empty($_POST["country"])) {
        $errors['country'] = "Please enter your country.";
    } else {
        $country = sanitize($_POST["country"]);
        if (!preg_match("/^[a-zA-Z\s]{2,50}$/", $country)) {
            $errors['country'] = "Country must be letters and spaces only.";
        }
    }

    // Validate username
    if (empty($_POST["username"])) {
        $errors['username'] = "Please choose a username.";
    } else {
        $username = sanitize($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9_]{5,20}$/", $username)) {
            $errors['username'] = "Username must be 5-20 characters (letters, numbers, underscores).";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $errors['password'] = "Please enter a password.";
    } else {
        $password = $_POST["password"];
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $password)) {
            $errors['password'] = "Password must be at least 8 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
        }
    }

    // Validate confirm password
    if (empty($_POST["confirm_password"])) {
        $errors['confirm_password'] = "Please confirm your password.";
    } else {
        $confirm_password = $_POST["confirm_password"];
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
    }

    // Check if username or email already exists
    if (empty($errors)) {
        $stmt = executeQuery($mysqli, "SELECT * FROM user WHERE username = ? OR email = ?", [$username, $email], 'ss');
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['username'] = "Username or email already exists.";
        }
    }

    // If no errors, insert user into database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (fullname, gender, dob, phone, email, street, city, state, zip, country, username, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$fullname, $gender, $dob, $phone, $email, $street, $city, $state, $zip, $country, $username, $hashed_password];
        $types = 'ssssssssssss';

        $stmt = executeQuery($mysqli, $sql, $params, $types);

        if ($stmt) {
            $registration_success = true;

            // Get inserted user ID
            $user_id = $mysqli->insert_id;

            $_SESSION['user'] = [
                'fullname' => $fullname,
                'gender' => $gender,
                'dob' => $dob,
                'phone' => $phone,
                'email' => $email,
                'street' => $street,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'country' => $country,
                'username' => $username
            ];

            // Set session variables consistent with login.php
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
        } else {
            $errors['database'] = "Failed to register user. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Boss's Motor Shop - Registration</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0d3b3f;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><i>Boss's Motor Shop</i></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link active" href="#" id="register-nav-link">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <?php if ($registration_success): ?>
          <div class="alert alert-success">
            <h2><i class="bi bi-check-circle"></i> Registration Successful!</h2>
            <p>Thank you for registering, <?php echo htmlspecialchars($fullname); ?>!</p>
            <a href="welcome.php" class="btn btn-primary">Continue to Your Account</a>
          </div>
        <?php else: ?>
          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul>
                <?php foreach ($errors as $error): ?>
                  <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form id="registration-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
            <!-- Personal Information Section -->
            <div class="form-section">
              <h2><i class="bi bi-person-vcard"></i> Personal Information</h2>
              <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" class="form-control <?php echo (!empty($errors['fullname'])) ? 'is-invalid' : ''; ?>" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['fullname'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select <?php echo (!empty($errors['gender'])) ? 'is-invalid' : ''; ?>" id="gender" name="gender" required>
                  <option value="" <?php echo empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                  <option value="male" <?php echo ($gender === 'male') ? 'selected' : ''; ?>>Male</option>
                  <option value="female" <?php echo ($gender === 'female') ? 'selected' : ''; ?>>Female</option>
                </select>
                <div class="invalid-feedback"><?php echo $errors['gender'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control <?php echo (!empty($errors['dob'])) ? 'is-invalid' : ''; ?>" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['dob'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control <?php echo (!empty($errors['phone'])) ? 'is-invalid' : ''; ?>" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['phone'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['email'] ?? ''; ?></div>
              </div>
            </div>

            <!-- Address Details Section -->
            <div class="form-section">
              <h2><i class="bi bi-house"></i> Address Details</h2>
              <div class="mb-3">
                <label for="street" class="form-label">Street</label>
                <input type="text" class="form-control <?php echo (!empty($errors['street'])) ? 'is-invalid' : ''; ?>" id="street" name="street" value="<?php echo htmlspecialchars($street); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['street'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control <?php echo (!empty($errors['city'])) ? 'is-invalid' : ''; ?>" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['city'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="state" class="form-label">Province/State</label>
                <input type="text" class="form-control <?php echo (!empty($errors['state'])) ? 'is-invalid' : ''; ?>" id="state" name="state" value="<?php echo htmlspecialchars($state); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['state'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="zip" class="form-label">Zip Code</label>
                <input type="text" class="form-control <?php echo (!empty($errors['zip'])) ? 'is-invalid' : ''; ?>" id="zip" name="zip" value="<?php echo htmlspecialchars($zip); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['zip'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control <?php echo (!empty($errors['country'])) ? 'is-invalid' : ''; ?>" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['country'] ?? ''; ?></div>
              </div>
            </div>

            <!-- Account Details Section -->
            <div class="form-section">
              <h2><i class="bi bi-shield-lock"></i> Account Details</h2>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control <?php echo (!empty($errors['username'])) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <div class="invalid-feedback"><?php echo $errors['username'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control <?php echo (!empty($errors['password'])) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                <div class="invalid-feedback"><?php echo $errors['password'] ?? ''; ?></div>
              </div>
              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control <?php echo (!empty($errors['confirm_password'])) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                <div class="invalid-feedback"><?php echo $errors['confirm_password'] ?? ''; ?></div>
              </div>
            </div>

            <div class="d-grid gap-2">
              <button type="reset" class="btn btn-outline-secondary">Reset Form</button>
              <button type="submit" class="btn btn-primary">Register Now</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php include 'includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
