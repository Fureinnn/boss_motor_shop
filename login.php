<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$pageTitle = "Login - Boss's Motor Shop";
include 'includes/header.php';

// Check if user is already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password']; // Don't sanitize passwords
    
    $stmt = executeQuery($mysqli, "SELECT * FROM user WHERE username = ?", [$username], 's');
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        
        // Check if user is admin
        $stmt = executeQuery($mysqli, "SELECT is_admin FROM profile WHERE user_id = ?", [$user['user_id']], 'i');
        $profile = $stmt->get_result()->fetch_assoc();
        
        if ($profile && $profile['is_admin']) {
            $_SESSION['is_admin'] = true;
            header("Location: admin/dashboard.php");
        } else {
            header("Location: profile.php");
        }
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 form-container">
            <h2 class="text-center mb-4">Login</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="text-center mt-3">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>