<?php
session_start();
include 'connect.php';

$error_msg = '';
$success_msg = '';

if($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        // Sanitize email input
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Prevent SQL injection using prepared statement
        $sql = "SELECT * FROM Register WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if(mysqli_num_rows($result) > 0) {
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_time'] = time(); // Add expiry time
            header('Location: reset_password.php');
            exit();
        } else {
            throw new Exception("Email not found in our records");
        }
    } catch (Exception $e) {
        $error_msg = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Premium Fitness Club</title>
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
            <h3>Forgot Password</h3>
            <?php if($error_msg): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_msg); ?></div>
            <?php endif; ?>
            <?php if($success_msg): ?>
                <div class="success-message"><?php echo htmlspecialchars($success_msg); ?></div>
            <?php endif; ?>
            
            <form action="forgot_password.php" method="POST" onsubmit="return validateForm()">
                <input type="email" 
                       name="email" 
                       id="email"
                       placeholder="Enter your email address" 
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                       title="Please enter a valid email address"
                       required />
                <button type="submit">Reset Password</button>
            </form>
            
            <a href="login2.php" class="toggle-link">Back to Login</a>
        </div>
        </body>
</html>

        <script>
        function validateForm() {
            const email = document.getElementById('email').value;
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            
            if (!emailPattern.test(email)) {
                alert('Please enter a valid email address');
                return false;
            }
            return true;
        }
        </script>
