<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Database connection
require_once 'connect.php';

// Validate and sanitize input
$user_id = $_SESSION['user_id'];
$full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
$mobile_no = filter_input(INPUT_POST, 'mobile_no', FILTER_SANITIZE_STRING);
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);

// Validate required fields
if (empty($full_name) || empty($mobile_no) || empty($address)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required'
    ]);
    exit;
}

// Validate mobile number format (10 digits)
if (!preg_match('/^[0-9]{10}$/', $mobile_no)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid mobile number format'
    ]);
    exit;
}

try {
    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE register SET full_name = ?, mobile_no = ?, address = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $full_name, $mobile_no, $address, $user_id);
    
    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['name'] = $full_name;
        $_SESSION['mobile'] = $mobile_no;
        $_SESSION['address'] = $address;
        
        // Return success response with updated data
        echo json_encode([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => [
                'name' => $full_name,
                'mobile' => $mobile_no,
                'address' => $address
            ]
        ]);
    } else {
        throw new Exception("Failed to update profile");
    }
} catch (Exception $e) {
    // Log error for debugging
    error_log("Profile Update Error: " . $e->getMessage());
    
    echo json_encode([
        'status' => 'error',
        'message' => 'An error occurred while updating your profile'
    ]);
}

// Close database connection
$stmt->close();
$conn->close();
?>