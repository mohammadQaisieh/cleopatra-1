<?php
session_start();

// If the user is already logged in, redirect to index.php
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include("./config.php");

// Initialize variables
$error = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to the database
    connect();

    // Retrieve and sanitize user input
    $username_or_email = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username_or_email) || empty($password)) {
        $error = "Please enter both username/email and password.";
    } else {
        // Prepare SQL statement to fetch user by username or email
        $query = "SELECT id, name, email, Role, password FROM users WHERE name = '$username_or_email' OR email = '$username_or_email' LIMIT 1";
        // print_r($query);
        $result = query($query);
        check($result);

        if ($user = mysqli_fetch_assoc($result)) {
            // Verify the password
            if ($password == $user['password'] ) {
                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['Role'] = $user['Role'];

                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Redirect to index.php
                if ($user['Role'] == "Admin") header("Location: index.php");
                else header("Location: products.php");
                exit();
            } else {
                // Invalid password
                $error = "Invalid username/email or password.";
            }
        } else {
            // User not found
            $error = "Invalid username/email or password.";
        }
    }

    // Close the database connection
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Include your CSS files or frameworks here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            border: 1px solid #e3e3e3;
            border-radius: 10px;
            background-color: #fff;
        }
        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container login-container">
        <div class="login-header">
            <h2>Login</h2>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username_or_email">Username or Email</label>
                <input type="text" name="username_or_email" id="username_or_email" class="form-control" required value="<?= htmlspecialchars($_POST['username_or_email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>

    <!-- Optional: Include Bootstrap JS for better styling -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
