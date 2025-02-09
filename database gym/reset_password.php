<?php
session_start();
include 'connect.php';

$error_msg = '';
$success_msg = '';

// Check for valid session
if(!isset($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
    exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        // Validate passwords
        if(!isset($_POST['new_password']) || !isset($_POST['confirm_password'])) {
            throw new Exception("Both password fields are required");
        }

        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Check password match
        if($new_password !== $confirm_password) {
            throw new Exception("Passwords do not match");
        }

        // Validate password strength
        if(strlen($new_password) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }

        // Hash password and get email
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($conn, $_SESSION['reset_email']);

        // Use prepared statement
        $sql = "UPDATE Register SET password=? WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if(!$stmt) {
            throw new Exception("Database error occurred");
        }

        mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error updating password");
        }

        if(mysqli_affected_rows($conn) > 0) {
            $success_msg = "Password updated successfully!";
            unset($_SESSION['reset_email']);
            header("refresh:2;url=login2.php"); // Redirect after 2 seconds
        } else {
            throw new Exception("No changes were made");
        }

        mysqli_stmt_close($stmt);

    } catch (Exception $e) {
        $error_msg = $e->getMessage();
        error_log("Password Reset Error: " . $e->getMessage()); // Server-side logging
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Premium Fitness Club</title>
    <style>
        /* Copy the same styles from login2.php */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            color: #000;
            margin: 50px auto;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background: #ff5722;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .error-message {
            background: #f44336;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        // Add this to your existing <style> section
.success-message {
    background: #4CAF50;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}
    </style>
</head>
<body>
<div class="form-container">
    <h3>Reset Password</h3>
    <?php if($error_msg): ?>
        <div class="error-message"><?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>
    <?php if($success_msg): ?>
        <div class="success-message"><?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>
    
    <form action="reset_password.php" method="POST" onsubmit="return validateForm()">
        <input type="password" 
               name="new_password" 
               id="new_password"
               placeholder="Enter new password" 
               required 
               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
               title="Password must be at least 8 characters long and include both letters and numbers" />
        <input type="password" 
               name="confirm_password" 
               id="confirm_password"
               placeholder="Confirm new password" 
               required />
        <button type="submit">Update Password</button>
    </form>
</div>
</body>
<script>
function validateForm() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;

    try {
        if (newPassword !== confirmPassword) {
            throw new Error('Passwords do not match');
        }
        if (newPassword.length < 8) {
            throw new Error('Password must be at least 8 characters long');
        }
        return true;
    } catch (error) {
        alert(error.message);
        return false;
    }
}
</script>
</html>