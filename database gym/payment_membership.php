<?php
session_start();
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Load membership rates from config
$config_file = __DIR__ . '/config/membership_rates.php';
if (file_exists($config_file)) {
    include $config_file;
} else {
    // Default values if config doesn't exist
    define('JOINING_FEE', 2000);
    define('MONTHLY_FEE', 999);
}

// Use the constants from config file
$joining_fee = JOINING_FEE;
$monthly_fee = MONTHLY_FEE;
$total_amount = $joining_fee + $monthly_fee;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_completed'])) {
    $payment_method = $_POST['payment_method'];
    $transaction_id = $_POST['transaction_id'];
    
    try {
        $conn->begin_transaction();

        $current_date = date('Y-m-d');
        $next_payment_date = date('Y-m-d', strtotime('+1 month'));

        // Insert joining fee record
        $stmt = $conn->prepare("INSERT INTO memberships (
            user_id, 
            joining_date, 
            last_payment_date, 
            next_payment_date, 
            membership_status, 
            payment_amount, 
            payment_type, 
            payment_status, 
            payment_method, 
            transaction_id
        ) VALUES (?, ?, ?, ?, 'active', ?, 'joining', 'completed', ?, ?)");
        
        $stmt->bind_param("isssdss", 
            $user_id,
            $current_date,
            $current_date,
            $next_payment_date,
            $joining_fee,
            $payment_method,
            $transaction_id
        );
        
        $stmt->execute();

        // Insert first month payment record
        $stmt = $conn->prepare("INSERT INTO memberships (
            user_id, 
            joining_date, 
            last_payment_date, 
            next_payment_date, 
            membership_status, 
            payment_amount, 
            payment_type, 
            payment_status, 
            payment_method, 
            transaction_id
        ) VALUES (?, ?, ?, ?, 'active', ?, 'monthly', 'completed', ?, ?)");
        
        $stmt->bind_param("isssdss", 
            $user_id,
            $current_date,
            $current_date,
            $next_payment_date,
            $monthly_fee,
            $payment_method,
            $transaction_id
        );
        
        $stmt->execute();

        $conn->commit();
        $_SESSION['payment_success'] = true;
        header("Location: login2.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $error = "Payment processing failed: " . $e->getMessage();
    }
}
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
    </style>
</head>
<body>
    <a href="register.php" class="back-button">
        <i class="fa fa-arrow-left"></i> Back to Registration
    </a>

    <div class="container">
        <div class="payment-container">
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

            <form method="POST" id="paymentForm">
                <div class="payment-method">
                    <h4>Select Payment Method</h4>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="upi" name="payment_method" value="upi" class="custom-control-input" required>
                        <label class="custom-control-label" for="upi">
                            <i class="fa fa-mobile-alt"></i> UPI (Google Pay/PhonePe/Paytm)
                        </label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="card" name="payment_method" value="card" class="custom-control-input">
                        <label class="custom-control-label" for="card">
                            <i class="fa fa-credit-card"></i> Debit/Credit Card
                        </label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="netbanking" name="payment_method" value="netbanking" class="custom-control-input">
                        <label class="custom-control-label" for="netbanking">
                            <i class="fa fa-university"></i> Net Banking
                        </label>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label for="transaction_id">Transaction ID</label>
                    <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
                    <small class="form-text text-muted">Please enter the transaction ID after completing your payment</small>
                </div>

                <input type="hidden" name="payment_completed" value="1">
                
                <button type="submit" class="btn btn-submit mt-4">Complete Payment</button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">By completing the payment, you agree to our terms and conditions</small>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        document.querySelector('.back-button').addEventListener('click', function(e) {
            e.preventDefault();
            if(confirm('Are you sure you want to go back? Your payment progress will be lost.')) {
                window.location.href = 'register.php';
            }
        });
    </script>
</body>
</html> 