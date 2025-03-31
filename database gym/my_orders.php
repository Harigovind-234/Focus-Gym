<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle order cancellation
if (isset($_POST['cancel_order'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    // First get the order details and current product stock
    $check_sql = "SELECT o.order_id, o.quantity, o.product_id, o.status, p.stock, p.stock_quantity 
                  FROM orders o 
                  INNER JOIN products p ON o.product_id = p.product_id 
                  WHERE o.order_id = ? AND o.user_id = ? AND o.status = 'Pending'";
    
    $check_stmt = $conn->prepare($check_sql);
    
    if($check_stmt === false) {
        die('Prepare Error: ' . $conn->error);
    }
    
    $check_stmt->bind_param("ii", $order_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // 1. Calculate new stock (current stock + cancelled quantity)
            $restored_stock = $order['stock'] + $order['quantity'];
            
            // 2. Update product stock
            $update_stock_sql = "UPDATE products 
                               SET stock = ?, 
                                   stock_quantity = stock_quantity + ?,
                                   updated_at = CURRENT_TIMESTAMP 
                               WHERE product_id = ?";
            
            $update_stock_stmt = $conn->prepare($update_stock_sql);
            
            if($update_stock_stmt === false) {
                throw new Exception('Prepare Error: ' . $conn->error);
            }
            
            $update_stock_stmt->bind_param("iii", 
                $restored_stock, 
                $order['quantity'], 
                $order['product_id']
            );
            
            if(!$update_stock_stmt->execute()) {
                throw new Exception('Failed to update stock');
            }
            
            // 3. Delete the order
            $delete_sql = "DELETE FROM orders 
                          WHERE order_id = ? AND user_id = ? AND status = 'Pending'";
            
            $delete_stmt = $conn->prepare($delete_sql);
            
            if($delete_stmt === false) {
                throw new Exception('Prepare Error: ' . $conn->error);
            }
            
            $delete_stmt->bind_param("ii", $order_id, $user_id);
            
            if(!$delete_stmt->execute()) {
                throw new Exception('Failed to delete order');
            }
            
            // If everything is successful, commit the transaction
            $conn->commit();
            $_SESSION['success_msg'] = "Order cancelled successfully. Stock restored to previous value.";
            
        } catch (Exception $e) {
            // If anything fails, rollback changes
            $conn->rollback();
            $_SESSION['error_msg'] = "Error: " . $e->getMessage();
            error_log("Order cancellation error: " . $e->getMessage());
        }
        
        // Close statements
        $update_stock_stmt->close();
        $delete_stmt->close();
    } else {
        $_SESSION['error_msg'] = "Order not found or already cancelled.";
    }
    
    $check_stmt->close();
    header("Location: my_orders.php");
    exit();
}

// Fetch user's orders with all necessary fields
$stmt = $conn->prepare("
    SELECT o.*, p.product_name, p.image_path, 
           o.total_price as total_amount,
           o.status as order_status,
           o.collection_time,
           o.payment_method,
           o.payment_status
    FROM orders o 
    JOIN products p ON o.product_id = p.product_id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #232d39;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background: #1a1a1a;
            padding: 15px 0;
            margin-bottom: 30px;
        }
        .navbar-brand {
            color: #ed563b !important;
            font-weight: 600;
            font-size: 24px;
        }
        .nav-link {
            color: #fff !important;
        }
        .orders-container {
            padding: 30px 0;
            min-height: calc(100vh - 100px);
        }
        .order-card {
            background: #1a1a1a;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .order-header {
            background: linear-gradient(135deg, #ed563b, #ff8d6b);
            padding: 15px 20px;
            color: white;
        }
        .order-body {
            padding: 20px;
            color: #fff;
        }
        .product-image {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            overflow: hidden;
        }
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .status-pending { background: #ffeeba; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-ready { background: #cce5ff; color: #004085; }
        .status-collected { background: #c3e6cb; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        .cancel-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .cancel-btn:hover {
            background: #c82333;
        }
        .collection-time {
            background: #333;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .bill-details {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 10px;
        }
        .bill-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #3a3a3a;
        }
        .bill-row.total {
            border-top: 2px solid #3a3a3a;
            border-bottom: none;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            color: #ed563b;
        }
        .payment-info {
            background: #333;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .payment-method, .payment-status {
            margin: 5px 0;
        }
        .payment-method i, .payment-status i {
            margin-right: 10px;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-warning {
            color: #ffc107 !important;
        }
        .text-primary {
            color: #ed563b !important;
        }
        .status-badge {
            font-weight: 600;
        }
        .collection-time {
            background: #333;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        .collection-time i {
            margin-right: 8px;
            color: #ed563b;
        }
    </style>
</head>
<body>
    <!-- Simple Navigation -->
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">GYM STORE</a>
        </div>
    </nav>

    <div class="orders-container">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-white">My Orders</h2>
                <a href="index.php#products" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
            
            <?php if ($orders->num_rows > 0): ?>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <div class="order-card">
                        <div class="order-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Order #<?php echo $order['order_id']; ?></h5>
                                <small><?php echo date('F d, Y h:i A', strtotime($order['created_at'])); ?></small>
                            </div>
                            <span class="status-badge status-<?php echo strtolower($order['order_status']); ?>">
                                <?php echo ucfirst($order['order_status']); ?>
                            </span>
                        </div>
                        
                        <div class="order-body">
                            <div class="row">
                                <!-- Product Details -->
                                <div class="col-md-6">
                                    <div class="d-flex gap-4">
                                        <div class="product-image">
                                            <img src="<?php echo htmlspecialchars($order['image_path']); ?>" 
                                                 alt="<?php echo htmlspecialchars($order['product_name']); ?>">
                                        </div>
                                        <div>
                                            <h5><?php echo htmlspecialchars($order['product_name']); ?></h5>
                                            <p>Quantity: <?php echo $order['quantity']; ?></p>
                                            
                                            <?php if ($order['collection_time']): ?>
                                                <div class="collection-time">
                                                    <i class="fas fa-clock"></i>
                                                    Collection Time: <?php echo date('F d, Y h:i A', strtotime($order['collection_time'])); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bill Details -->
                                <div class="col-md-6">
                                    <div class="bill-details">
                                        <h6 class="text-primary mb-3">Bill Details</h6>
                                        <div class="bill-row">
                                            <span>Subtotal:</span>
                                            <span>₹<?php echo number_format($order['total_amount'] / 1.18, 2); ?></span>
                                        </div>
                                        <div class="bill-row">
                                            <span>GST (18%):</span>
                                            <span>₹<?php echo number_format($order['total_amount'] - ($order['total_amount'] / 1.18), 2); ?></span>
                                        </div>
                                        <div class="bill-row total">
                                            <span>Total Amount:</span>
                                            <span>₹<?php echo number_format($order['total_amount'], 2); ?></span>
                                        </div>
                                        
                                        <div class="payment-info mt-3">
                                            <div class="payment-method">
                                                <i class="fas <?php echo $order['payment_method'] == 'upi' ? 'fa-mobile-alt' : 'fa-money-bill-alt'; ?>"></i>
                                                Payment Method: <?php echo ucfirst($order['payment_method'] ?? 'Not specified'); ?>
                                            </div>
                                            <div class="payment-status">
                                                <i class="fas fa-circle <?php echo $order['payment_status'] == 'paid' ? 'text-success' : 'text-warning'; ?>"></i>
                                                Payment Status: <?php echo ucfirst($order['payment_status'] ?? 'pending'); ?>
                                            </div>
                                        </div>

                                        <?php if ($order['order_status'] == 'pending'): ?>
                                            <form method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                                <button type="submit" name="cancel_order" class="cancel-btn">
                                                    <i class="fas fa-times"></i> Cancel Order
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center text-white">
                    <p>No orders found.</p>
                    <a href="index.php#products" class="btn btn-primary">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 