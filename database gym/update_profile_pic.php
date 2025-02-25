<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

if (!isset($_FILES['profile_pic'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$upload_dir = 'uploads/profile/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$file = $_FILES['profile_pic'];
$file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed_ext = ['jpg', 'jpeg', 'png'];

if (!in_array($file_ext, $allowed_ext)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
    exit;
}

$new_filename = $_SESSION['user_id'] . '_' . time() . '.' . $file_ext;
$upload_path = $upload_dir . $new_filename;

if (move_uploaded_file($file['tmp_name'], $upload_path)) {
    $query = "UPDATE register SET profile_pic = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $new_filename, $_SESSION['user_id']);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'success' => true,
            'image_path' => $upload_path
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database update failed'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to upload file'
    ]);
} 