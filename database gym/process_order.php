<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Please log in to place an order'
        ]);
        exit;
    }

    // Validate inputs
    if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields'
        ]);
        exit;
    }

    try {
        // Sanitize inputs
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity']);
        $user_id = intval($_SESSION['user_id']);
        $order_date = date('Y-m-d H:i:s');

        // Validate quantity
        if ($quantity <= 0) {
            throw new Exception('Invalid quantity');
        }

        // Begin transaction
        mysqli_begin_transaction($conn);

        // Insert order
        $sql = "INSERT INTO orders (user_id, product_id, quantity, order_date, status) 
                VALUES (?, ?, ?, ?, 'pending')";
        
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "iiis", $user_id, $product_id, $quantity, $order_date);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to execute statement: ' . mysqli_stmt_error($stmt));
        }

        $order_id = mysqli_insert_id($conn);

        // Commit transaction
        mysqli_commit($conn);

        echo json_encode([
            'success' => true,
            'order_id' => $order_id,
            'message' => 'Order placed successfully'
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        
        echo json_encode([
            'success' => false,
            'message' => 'Error processing order: ' . $e->getMessage()
        ]);
    }

} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

// Close connection
mysqli_close($conn);
?> 