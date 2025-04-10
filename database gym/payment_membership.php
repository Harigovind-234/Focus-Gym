<?php
// Replace the require_once line with this check
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    die("Please install dependencies using Composer. Run 'composer install' in the project directory.");
}

session_start();
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Define membership rates
define('JOINING_FEE', 2000);
define('MONTHLY_FEE', 999);

$joining_fee = JOINING_FEE;
$monthly_fee = MONTHLY_FEE;
$total_amount = $joining_fee + $monthly_fee;

// Razorpay credentials
function getRazorpayKey() {
    return 'rzp_test_Fur0pLo5d2MztK';
}

function getRazorpaySecret() {
    return 'TqC7xFxWWnBUsnAzznEB1YaT';
}

// Create Razorpay order
function createRazorpayOrder($amount) {
    $api_key = getRazorpayKey();
    $api_secret = getRazorpaySecret();
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.razorpay.com/v1/orders");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $api_key . ":" . $api_secret);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'amount' => $amount * 100,
        'currency' => 'INR',
        'receipt' => 'order_' . time(),
        'payment_capture' => 1
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($result, true);
}

// Create order before displaying the form
$order = createRazorpayOrder($total_amount);
$orderId = $order['id'] ?? null;

if (!$orderId) {
    error_log("Razorpay Order Creation Failed: " . print_r($order, true));
}

function handlePaymentError($message) {
    error_log("Payment Error: " . $message);
    $_SESSION['payment_error'] = $message;
    return false;
}

$razorpayKey = getRazorpayKey();

// Add this after your session_start()
if (isset($_SESSION['payment_error'])) {
    $error_message = $_SESSION['payment_error'];
    unset($_SESSION['payment_error']);
}

// Handle successful payment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['razorpay_payment_id'])) {
    try {
        $payment_id = $_POST['razorpay_payment_id'];
        
        // Debug logging
        error_log("Payment processing started for user_id: " . $user_id);
        error_log("Payment ID: " . $payment_id);

        // Verify user exists
        $check_user = "SELECT user_id FROM register WHERE user_id = ?";
        $stmt = $conn->prepare($check_user);
        if (!$stmt) {
            throw new Exception("Error checking user: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("User not found");
        }
        $stmt->close();

        // Start transaction
        $conn->begin_transaction();

        $current_date = date('Y-m-d');
        $next_payment_date = date('Y-m-d', strtotime('+1 month'));

        // Insert joining fee record
        $joining_sql = "INSERT INTO memberships 
                       (user_id, joining_date, last_payment_date, next_payment_date, 
                       membership_status, payment_amount, payment_type, payment_status, 
                       payment_method, transaction_id, rate_joining_fee, rate_monthly_fee) 
                       VALUES 
                       (?, ?, ?, ?, 'active', ?, 'joining', 'completed', 
                       'razorpay', ?, 2000.00, 999.00)";
        
        $stmt = $conn->prepare($joining_sql);
        if (!$stmt) {
            throw new Exception("Error preparing joining fee query: " . $conn->error);
        }
        $stmt->bind_param("isssds", $user_id, $current_date, $current_date, 
                                   $next_payment_date, $joining_fee, $payment_id);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting joining fee: " . $stmt->error);
        }
        $stmt->close();

        // Insert monthly fee record
        $monthly_sql = "INSERT INTO memberships 
                       (user_id, joining_date, last_payment_date, next_payment_date, 
                       membership_status, payment_amount, payment_type, payment_status, 
                       payment_method, transaction_id, rate_joining_fee, rate_monthly_fee) 
                       VALUES 
                       (?, ?, ?, ?, 'active', ?, 'monthly', 'completed', 
                       'razorpay', ?, 2000.00, 999.00)";
        
        $stmt = $conn->prepare($monthly_sql);
        if (!$stmt) {
            throw new Exception("Error preparing monthly fee query: " . $conn->error);
        }
        $stmt->bind_param("isssds", $user_id, $current_date, $current_date, 
                                   $next_payment_date, $monthly_fee, $payment_id);
        if (!$stmt->execute()) {
            throw new Exception("Error inserting monthly fee: " . $stmt->error);
        }
        $stmt->close();

        // Update user status
        $update_sql = "UPDATE register SET status = 'active' WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        if (!$stmt) {
            throw new Exception("Error preparing status update: " . $conn->error);
        }
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating user status: " . $stmt->error);
        }
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Log success
        error_log("Payment processed successfully for user_id: " . $user_id);

        // Set success message
        $_SESSION['payment_success'] = "Payment Successful! Your membership is now active. Please login to continue.";
        
        // Redirect with success message
        echo "<script>
            alert('Payment Successful! Your membership is now active.');
            window.location.href = 'login2.php';
        </script>";
        exit();

    } catch (Exception $e) {
        // Rollback transaction
        try {
            $conn->rollback();
        } catch (Exception $rollbackError) {
            error_log("Rollback failed: " . $rollbackError->getMessage());
        }

        // Log the error
        error_log("Payment Error for user_id " . $user_id . ": " . $e->getMessage());
        error_log("SQL State: " . $conn->sqlstate);
        error_log("Error Code: " . $conn->errno);

        // Set error message with more detail
        $_SESSION['payment_error'] = "Payment processing failed. Error: " . $e->getMessage();
        
        // Redirect with error message
        echo "<script>
            alert('Payment Failed: " . addslashes($e->getMessage()) . "\\nPlease try again or contact support.');
            window.location.href = 'payment_membership.php';
        </script>";
        exit();
    }
}

error_log("CSV File Path: " . __DIR__ . '/rzp.csv');
error_log("CSV File Exists: " . (file_exists(__DIR__ . '/rzp.csv') ? 'Yes' : 'No'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Payment - FOCUS GYM</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <style>
        body {
            background: #f8f9fa;
            padding-top: 50px;
            position: relative;
        }
        .payment-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .payment-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .payment-method label {
            display: block;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-method label:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }
        .payment-method input[type="radio"]:checked + label {
            border-color: #007bff;
            background: #f8f9fa;
        }
        .btn-submit {
            background: #ed563b;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            background: #dc472e;
            transform: translateY(-2px);
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 8px 15px;
            background: #232d39;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .back-button:hover {
            background: #ed563b;
            color: white;
            text-decoration: none;
            transform: translateX(-5px);
        }
        .fee-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .fee-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .fee-card .amount {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ed563b;
            margin: 10px 0;
        }

        .fee-card h5 {
            color: #232d39;
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right;
        }

        .payment-option {
            display: block;
            padding: 20px !important;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 15px 0 !important;
        }

        .payment-option:hover {
            border-color: #ed563b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .payment-option-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .payment-option i {
            font-size: 24px;
            color: #ed563b;
        }

        .payment-option span {
            flex-grow: 1;
            font-size: 16px;
            font-weight: 500;
        }

        .payment-logo {
            height: 25px;
            object-fit: contain;
        }

        #rzp-button {
            background: #ed563b;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
        }

        #rzp-button:hover {
            background: #dc472e;
        }

        .payment-success {
            text-align: center;
            padding: 30px;
            background: #d4edda;
            border-radius: 10px;
            margin-top: 20px;
            display: none;
        }

        .payment-success i {
            font-size: 48px;
            color: #28a745;
            margin-bottom: 15px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <a href="register.php" class="back-button">
        <i class="fa fa-arrow-left"></i> Back to Registration
    </a>

    <div class="container">
        <div class="payment-container">
            <?php if (isset($_SESSION['payment_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                        echo $_SESSION['payment_error'];
                        unset($_SESSION['payment_error']);
                    ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="payment-header">
                <h2>Complete Your Membership</h2>
                <p>Welcome to Focus Gym! Please complete your payment to activate your membership.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="payment-details">
                <h4>Payment Summary</h4>
                <div class="row mt-3">
                    <div class="col-8">Joining Fee</div>
                    <div class="col-4 text-right">₹<?php echo number_format($joining_fee, 2); ?></div>
                </div>
                <div class="row mt-2">
                    <div class="col-8">First Month Fee</div>
                    <div class="col-4 text-right">₹<?php echo number_format($monthly_fee, 2); ?></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-8"><strong>Total Amount</strong></div>
                    <div class="col-4 text-right"><strong>₹<?php echo number_format($total_amount, 2); ?></strong></div>
                </div>
            </div>

            <div class="rate-info mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="fee-card">
                            <h5>Joining Fee</h5>
                            <div class="amount">₹<?php echo number_format($joining_fee, 2); ?></div>
                            <small class="text-muted">One-time payment for new members</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fee-card">
                            <h5>Monthly Fee</h5>
                            <div class="amount">₹<?php echo number_format($monthly_fee, 2); ?></div>
                            <small class="text-muted">Regular monthly membership fee</small>
                        </div>
                    </div>
                </div>
            </div>

            <form id="paymentForm" method="POST">
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="<?php echo $orderId; ?>">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                
                <button type="button" id="rzp-button">
                    Pay ₹<?php echo number_format($total_amount, 2); ?>
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">By completing the payment, you agree to our terms and conditions</small>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.querySelector('.back-button').addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm('Are you sure you want to go back? Your payment progress will be lost.')) {
                window.location.href = 'register.php';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                "key": "<?php echo getRazorpayKey(); ?>",
                "amount": "<?php echo $total_amount * 100; ?>",
                "currency": "INR",
                "name": "Focus Gym",
                "description": "Membership Payment",
                "handler": function (response) {
                    // Show processing message
                    document.getElementById('rzp-button').disabled = true;
                    document.getElementById('rzp-button').textContent = 'Processing Payment...';
                    
                    // Set form values
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    
                    // Submit form
                    document.getElementById('paymentForm').submit();
                },
                "modal": {
                    "ondismiss": function() {
                        alert("Payment cancelled. Please try again.");
                    }
                },
                "prefill": {
                    "name": "<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>",
                    "email": "<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>",
                    "contact": "<?php echo isset($_SESSION['mobile_no']) ? htmlspecialchars($_SESSION['mobile_no']) : ''; ?>"
                },
                "theme": {
                    "color": "#ed563b"
                }
            };

            document.getElementById('rzp-button').onclick = function(e) {
                e.preventDefault();
                var rzp1 = new Razorpay(options);
                rzp1.open();
            };
        });
    </script>
</body>
</html> 