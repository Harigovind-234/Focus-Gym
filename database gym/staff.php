<?php
session_start();
require_once 'connect.php';

// Check if user is logged in as staff
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
//     header('Location: staff.php');
//     exit();


// Fetch staff details - Fixed query
$email = $_SESSION['email'];
$sql = "SELECT r.*, l.role,l.email
        FROM register r 
        INNER JOIN login l ON r.user_id = l.user_id
        WHERE l.email = ?";

// Use prepared statement to prevent SQL injection
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$staff = mysqli_fetch_assoc($result);

if (!$staff) {
    die("Error fetching staff details");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Staff Dashboard - Focus Gym</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">
    
    <style>
        .staff-dashboard {
            padding-top: 120px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 20px;
        }

        .profile-header {
            background: #232d39;
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #ed563b;
            margin: 0 auto 20px;
            overflow: hidden;
            background: #fff;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 600;
            margin: 10px 0;
        }

        .profile-role {
            display: inline-block;
            background: #ed563b;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
        }

        .profile-info {
            padding: 30px;
        }

        .info-group {
            margin-bottom: 25px;
        }

        .info-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #232d39;
            font-size: 16px;
            font-weight: 500;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #ed563b;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            justify-content: center;
        }

        .action-btn {
            background: #ed563b;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #f9735b;
            transform: translateY(-2px);
        }

        .header-area {
            background: #232d39 !important;
        }

        .nav li a {
            color: white !important;
        }

        .nav li a:hover {
            color: #ed563b !important;
        }

        @media (max-width: 768px) {
            .profile-card {
                margin: 10px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
        }

        /* Enhanced Styles */
        .section {
            padding: 100px 0;
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .section-title p {
            color: #fff;
        }

        /* Profile Section */
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 20px;
        }

        .profile-header {
            background: #232d39;
            color: white;
            padding: 40px;
            text-align: center;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 5px solid #ed563b;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Schedule Section */
        .schedule-wrapper {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .schedule-actions {
            margin-bottom: 20px;
        }

        .table th {
            background: #232d39;
            color: white;
        }

        .table td {
            vertical-align: middle;
            color: black;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            margin: 0 2px;
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background: #232d39;
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .close {
            color: white;
        }

        .form-control:focus {
            border-color: #ed563b;
            box-shadow: 0 0 0 0.2rem rgba(237, 86, 59, 0.25);
        }

        .btn-gradient {
            background: linear-gradient(45deg, #ed563b, #f9735b);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(237, 86, 59, 0.3);
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            margin-top: -15px;
        }

        .custom-table thead th {
            background: #232d39;
            color: white;
            padding: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
            border: none;
        }

        .day-header {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .day-subtitle {
            display: block;
            font-size: 12px;
            color: rgba(255,255,255,0.7);
            font-weight: normal;
        }

        .time-slot {
            background: #f8f9fa;
            width: 180px;
        }

        .time-main {
            display: block;
            font-weight: 600;
            color: #232d39;
            font-size: 15px;
        }

        .time-sub {
            display: block;
            font-size: 12px;
            color: #666;
        }

        .workout-cell {
            padding: 15px !important;
        }

        .workout-type {
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .workout-type i {
            font-size: 20px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .workout-name {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .workout-intensity {
            display: block;
            font-size: 12px;
            opacity: 0.8;
        }

        /* Workout Type Colors */
        .cardio { background: rgba(52, 191, 163, 0.1); color: #34bfa3; }
        .strength { background: rgba(88, 103, 221, 0.1); color: #5867dd; }
        .hiit { background: rgba(255, 184, 34, 0.1); color: #ffb822; }
        .weights { background: rgba(255, 168, 0, 0.1); color: #ffa800; }
        .yoga { background: rgba(156, 39, 176, 0.1); color: #9c27b0; }

        /* Hover Effects */
        .workout-type:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: none;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .btn-action.edit {
            background: rgba(88, 103, 221, 0.1);
            color: #5867dd;
        }

        .btn-action.delete {
            background: rgba(237, 86, 59, 0.1);
            color: #ed563b;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .custom-table {
                min-width: 1000px;
            }
            
            .schedule-wrapper {
                overflow-x: auto;
            }
        }

        /* Workout Plans */
        .plan-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-10px);
        }

        .plan-header {
            padding: 20px;
            text-align: center;
            color: white;
        }

        .plan-header.beginner { background: #28a745; }
        .plan-header.intermediate { background: #007bff; }
        .plan-header.advanced { background: #dc3545; }

        .plan-body {
            padding: 30px;
        }

        .plan-body ul {
            list-style: none;
            padding: 0;
        }

        .plan-body ul li {
            margin-bottom: 15px;
            color: #666;
        }

        .plan-body ul li i {
            color: #ed563b;
            margin-right: 10px;
        }

        /* Members Section */
        .members-wrapper {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .members-card {
            overflow-x: auto;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .section {
                padding: 60px 0;
            }
            
            .section-title h2 {
                font-size: 30px;
            }
        }

        /* Fixed Navigation Styles */
        .header-area {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            height: 80px;
            background: #232d39 !important;
            transition: none;
        }

        /* Profile Styles */
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 100px;
        }

        .profile-header {
            background: #232d39;
            color: white;
            padding: 40px;
            text-align: center;
        }

        .profile-avatar-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 5px solid #ed563b;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-avatar:hover .avatar-overlay {
            opacity: 1;
        }

        .upload-icon {
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        .profile-info {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
        }

        .form-control:focus {
            border-color: #ed563b;
            box-shadow: 0 0 0 0.2rem rgba(237,86,59,.25);
        }

        html {
            scroll-behavior: smooth;
        }

        /* Smooth transition for active state */
        .nav a {
            transition: all 0.3s ease-in-out;
        }

        .nav a.active {
            color: #ed563b !important;
            position: relative;
        }

        .nav a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background-color: #ed563b;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .staff-profile-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .profile-sidebar {
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }

        .profile-image-container {
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 180px;
            height: 180px;
            margin: 0 auto;
            position: relative;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.02);
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-placeholder i {
            font-size: 64px;
            color: #dee2e6;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            color: white;
        }

        .avatar-overlay i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .profile-avatar:hover .avatar-overlay {
            opacity: 1;
        }

        .profile-basic-info {
            margin-top: 20px;
        }

        .profile-basic-info h2 {
            margin: 15px 0 10px;
            color: #333;
            font-size: 24px;
        }

        .staff-badge {
            background: #ed563b;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            margin: 10px 0;
        }

        .staff-since {
            color: #6c757d;
            font-size: 14px;
        }

        .quick-stats {
            display: grid;
            gap: 15px;
        }

        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-item i {
            font-size: 24px;
            color: #ed563b;
        }

        .stat-details {
            text-align: left;
        }

        .stat-number {
            display: block;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            font-size: 13px;
            color: #6c757d;
        }

        .profile-main-content {
            padding: 20px;
        }

        .profile-action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .edit-profile-btn {
            background: #ed563b;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s;
            color: white;
            font-weight: 500;
        }

        .edit-profile-btn:hover {
            background: #dc472c;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(237, 86, 59, 0.2);
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #28a745;
            font-size: 14px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            display: inline-block;
        }

        .info-cards-container {
            display: grid;
            gap: 20px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
        }

        .card-header {
            padding: 20px;
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            border-bottom: 1px solid #dee2e6;
        }

        .card-header h4 {
            margin: 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            color: #ed563b;
        }

        .card-content {
            padding: 20px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }

        .info-item:hover {
            background-color: #f8f9fa;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #6c757d;
            font-weight: 500;
        }

        .info-value {
            color: #333;
        }

        .performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 20px;
        }

        .metric-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .metric-value {
            font-size: 24px;
            font-weight: bold;
            color: #ed563b;
            margin-bottom: 5px;
        }

        .metric-label {
            font-size: 13px;
            color: #6c757d;
        }

        @media (max-width: 992px) {
            .staff-profile-container {
                grid-template-columns: 1fr;
            }
            
            .profile-sidebar {
                max-width: 400px;
                margin: 0 auto;
            }
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: linear-gradient(145deg, #f8f9fa, #ffffff);
            border-bottom: 1px solid #dee2e6;
            border-radius: 15px 15px 0 0;
            padding: 20px;
        }

        .modal-title {
            color: #333;
            font-weight: 600;
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 12px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #ed563b;
            box-shadow: 0 0 0 0.2rem rgba(237, 86, 59, 0.25);
        }

        .modal-footer {
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 15px 15px;
            padding: 20px;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #ed563b;
            border: none;
        }

        .btn-primary:hover {
            background: #dc472c;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(237, 86, 59, 0.2);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.2em;
            margin-right: 8px;
        }

        .close:focus {
            outline: none;
        }

        .modal-backdrop.show {
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="index.php" class="logo">Focus<em> Gym</em></a>
                        <ul class="nav">
                            <li><a href="#profile">Profile</a></li>
                            <li><a href="#schedule">Schedule</a></li>
                            <li><a href="#workoutplans">Workout Plans</a></li>
                            <li><a href="#members">Members</a></li>
                            <li class="main-button"><a href="logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- ***** Main Content Start ***** -->
    <div class="main-content">
        <!-- Updated Profile Section -->
        <section class="section" id="profile">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="staff-profile-container">
                            <!-- Left Sidebar -->
                            <div class="profile-sidebar">
                                <div class="profile-image-container">
                                    <div class="profile-avatar" onclick="document.getElementById('profile_pic').click()">
                                        <?php if (!empty($_SESSION['profile_pic'])): ?>
                                            <img src="uploads/profile/<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile Picture" class="avatar-image">
                                        <?php else: ?>
                                            <div class="avatar-placeholder">
                                                <i class="fa fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="avatar-overlay">
                                            <i class="fa fa-camera"></i>
                                            <span>Update Photo</span>
                                        </div>
                                    </div>
                                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="display: none;">
                                </div>
                                <div class="profile-basic-info">
                                    <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                                    <span class="staff-badge">Staff Member</span>
                                    <p class="staff-since">Staff since <?php echo date('F j, Y', strtotime($_SESSION['date'])); ?></p>
                                </div>
                            </div>

                            <!-- Main Content -->
                            <div class="profaddile-main-content">
                                <!-- Action Bar -->
                                <div class="profile-action-bar">
                                    <button type="button" class="btn btn-primary edit-profile-btn" data-toggle="modal" data-target="#editProfileModal">
                                        <i class="fa fa-edit"></i> Edit Profile
                                    </button>
                                </div>

                                <!-- Info Cards Container -->
                                <div class="info-cards-container">
                                    <!-- Personal Information Card -->
                                    <div class="info-card">
                                        <div class="card-header">
                                            <h4><i class="fa fa-user-circle"></i> Personal Information</h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="info-item">
                                                <span class="info-label">Email</span>
                                                <span class="info-value" id="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Phone</span>
                                                <span class="info-value" id="profile-phone"><?php echo htmlspecialchars($_SESSION['mobile']); ?></span>
                                            </div>
                                            <div class="info-item">
                                                <span class="info-label">Address</span>
                                                <span class="info-value" id="profile-address"><?php echo htmlspecialchars($_SESSION['address']); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Schedule Section -->
        <section id="schedule" class="section schedule-section">
            <div class="container">
                <div class="section-title">
                    <h2>Weekly <em>Schedule</em></h2>
                    <p>Manage your training schedule</p>
                </div>
                <div class="schedule-wrapper">
                    <div class="schedule-actions mb-4">
                        <button class="btn btn-gradient" onclick="addNewRow()">
                            <i class="fa fa-plus-circle"></i> Add New Time Slot
                        </button>
                    </div>
                    <div class="timetable-card">
                        <table class="table table-hover custom-table" id="scheduleTable">
                            <thead>
                                <tr>
                                    <th class="time-column">Time Slot</th>
                                    <th>
                                        <span class="day-header">Monday</span>
                                        <span class="day-subtitle">Start Your Week Strong</span>
                                    </th>
                                    <th>
                                        <span class="day-header">Tuesday</span>
                                        <span class="day-subtitle">Power Through</span>
                                    </th>
                                    <th>
                                        <span class="day-header">Wednesday</span>
                                        <span class="day-subtitle">Mid-Week Energy</span>
                                    </th>
                                    <th>
                                        <span class="day-header">Thursday</span>
                                        <span class="day-subtitle">Keep Pushing</span>
                                    </th>
                                    <th>
                                        <span class="day-header">Friday</span>
                                        <span class="day-subtitle">Finish Strong</span>
                                    </th>
                                    <th class="action-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="time-slot">
                                        <span class="time-main">6:00 AM - 8:00 AM</span>
                                        <span class="time-sub">Early Bird Session</span>
                                    </td>
                                    <td class="workout-cell">
                                        <div class="workout-type cardio">
                                            <i class="fa fa-running"></i>
                                            <span class="workout-name">Cardio & Warmup</span>
                                            <span class="workout-intensity">Medium Intensity</span>
                                        </div>
                                    </td>
                                    <td class="workout-cell">
                                        <div class="workout-type strength">
                                            <i class="fa fa-dumbbell"></i>
                                            <span class="workout-name">Strength Training</span>
                                            <span class="workout-intensity">High Intensity</span>
                                        </div>
                                    </td>
                                    <td class="workout-cell">
                                        <div class="workout-type hiit">
                                            <i class="fa fa-bolt"></i>
                                            <span class="workout-name">HIIT Workout</span>
                                            <span class="workout-intensity">High Intensity</span>
                                        </div>
                                    </td>
                                    <td class="workout-cell">
                                        <div class="workout-type weights">
                                            <i class="fa fa-weight"></i>
                                            <span class="workout-name">Weight Training</span>
                                            <span class="workout-intensity">High Intensity</span>
                                        </div>
                                    </td>
                                    <td class="workout-cell">
                                        <div class="workout-type yoga">
                                            <i class="fa fa-peace"></i>
                                            <span class="workout-name">Flexibility & Yoga</span>
                                            <span class="workout-intensity">Low Intensity</span>
                                        </div>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn-action edit" onclick="editRow(this)" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn-action delete" onclick="deleteRow(this)" title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add Modal for Editing -->
        <div class="modal fade" id="scheduleModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="scheduleForm">
                            <div class="form-group">
                                <label>Time Slot</label>
                                <input type="text" class="form-control" id="timeSlot" required>
                            </div>
                            <div class="form-group">
                                <label>Monday</label>
                                <input type="text" class="form-control" id="monday" required>
                            </div>
                            <div class="form-group">
                                <label>Tuesday</label>
                                <input type="text" class="form-control" id="tuesday" required>
                            </div>
                            <div class="form-group">
                                <label>Wednesday</label>
                                <input type="text" class="form-control" id="wednesday" required>
                            </div>
                            <div class="form-group">
                                <label>Thursday</label>
                                <input type="text" class="form-control" id="thursday" required>
                            </div>
                            <div class="form-group">
                                <label>Friday</label>
                                <input type="text" class="form-control" id="friday" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="saveSchedule()">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workout Plans Section -->
        <section id="workoutplans" class="section workout-section">
            <div class="container">
                <div class="section-title">
                    <h2>Workout <em>Plans</em></h2>
                    <p>Customized training programs for different fitness levels</p>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="plan-card">
                            <div class="plan-header beginner">
                                <h3>Beginner</h3>
                            </div>
                            <div class="plan-body">
                                <ul>
                                    <li><i class="fa fa-check"></i> 15 min cardio warmup</li>
                                    <li><i class="fa fa-check"></i> Basic strength exercises</li>
                                    <li><i class="fa fa-check"></i> Core training</li>
                                    <li><i class="fa fa-check"></i> Light weight training</li>
                                    <li><i class="fa fa-check"></i> Stretching</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="plan-card">
                            <div class="plan-header intermediate">
                                <h3>Intermediate</h3>
                            </div>
                            <div class="plan-body">
                                <ul>
                                    <li><i class="fa fa-check"></i> 20 min HIIT cardio</li>
                                    <li><i class="fa fa-check"></i> Advanced strength training</li>
                                    <li><i class="fa fa-check"></i> Circuit training</li>
                                    <li><i class="fa fa-check"></i> Moderate weight lifting</li>
                                    <li><i class="fa fa-check"></i> Core strengthening</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="plan-card">
                            <div class="plan-header advanced">
                                <h3>Advanced</h3>
                            </div>
                            <div class="plan-body">
                                <ul>
                                    <li><i class="fa fa-check"></i> 30 min intense cardio</li>
                                    <li><i class="fa fa-check"></i> Heavy weight training</li>
                                    <li><i class="fa fa-check"></i> Complex circuit routines</li>
                                    <li><i class="fa fa-check"></i> CrossFit exercises</li>
                                    <li><i class="fa fa-check"></i> Advanced HIIT</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Members Section -->
        <section id="members" class="section members-section">
            <div class="container">
                <div class="section-title">
                    <h2>Assigned <em>Members</em></h2>
                    <p>View your assigned gym members</p>
                </div>
                <div class="members-wrapper">
                    <div class="members-card">
                        <div class="table-responsive">
                            <table class="table table-hover custom-table" id="membersTable">
                                <thead>
                                    <tr>
                                        <th>Member Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Assigned Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // Get the staff's user_id from the previously fetched staff details
                                $staff_id = $staff['user_id'];

                                // Query to fetch assigned members
                                $assigned_query = "SELECT r.*, l.email, sam.assigned_date 
                                                 FROM StaffAssignedMembers sam
                                                 JOIN register r ON sam.member_id = r.user_id
                                                 JOIN login l ON r.user_id = l.user_id
                                                 WHERE sam.trainer_id = ? AND l.role = 'member'
                                                 ORDER BY sam.assigned_date DESC";
                                
                                $stmt = mysqli_prepare($conn, $assigned_query);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "i", $staff_id);
                                    mysqli_stmt_execute($stmt);
                                    $assigned_result = mysqli_stmt_get_result($stmt);
                                    
                                    if (mysqli_num_rows($assigned_result) > 0) {
                                        while ($member = mysqli_fetch_assoc($assigned_result)) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($member['full_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($member['email']) . "</td>";
                                            echo "<td>" . htmlspecialchars($member['mobile_no']) . "</td>";
                                            echo "<td>" . htmlspecialchars($member['gender']) . "</td>";
                                            echo "<td>" . date('Y-m-d', strtotime($member['assigned_date'])) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No members currently assigned</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>Error preparing query</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add this modal code after your profile section -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Staff Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="alertPlaceholder"></div>
                        <form id="profileForm" method="POST" action="update_profile.php">
                            <div class="form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="mobile_no">Mobile Number</label>
                                <input type="tel" class="form-control" id="mobile_no" name="mobile_no" 
                                       value="<?php echo htmlspecialchars($_SESSION['mobile'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($_SESSION['address'] ?? ''); ?></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="updateProfile()">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
    let currentRow = null;

    function addNewRow() {
        $('#scheduleModal').modal('show');
        currentRow = null;
        clearForm();
    }

    function editRow(button) {
        currentRow = $(button).closest('tr');
        const cells = currentRow.find('td');
        
        $('#timeSlot').val(cells.eq(0).text());
        $('#monday').val(cells.eq(1).text());
        $('#tuesday').val(cells.eq(2).text());
        $('#wednesday').val(cells.eq(3).text());
        $('#thursday').val(cells.eq(4).text());
        $('#friday').val(cells.eq(5).text());
        
        $('#scheduleModal').modal('show');
    }

    function deleteRow(button) {
        if(confirm('Are you sure you want to delete this time slot?')) {
            $(button).closest('tr').remove();
        }
    }

    function saveSchedule() {
        const newData = {
            time: $('#timeSlot').val(),
            monday: $('#monday').val(),
            tuesday: $('#tuesday').val(),
            wednesday: $('#wednesday').val(),
            thursday: $('#thursday').val(),
            friday: $('#friday').val()
        };
        
        if(currentRow) {
            // Update existing row
            const cells = currentRow.find('td');
            cells.eq(0).text(newData.time);
            cells.eq(1).text(newData.monday);
            cells.eq(2).text(newData.tuesday);
            cells.eq(3).text(newData.wednesday);
            cells.eq(4).text(newData.thursday);
            cells.eq(5).text(newData.friday);
        } else {
            // Add new row
            const newRow = `
                <tr>
                    <td>${newData.time}</td>
                    <td>${newData.monday}</td>
                    <td>${newData.tuesday}</td>
                    <td>${newData.wednesday}</td>
                    <td>${newData.thursday}</td>
                    <td>${newData.friday}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="editRow(this)">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#scheduleTable tbody').append(newRow);
        }
        
        $('#scheduleModal').modal('hide');
    }

    function clearForm() {
        $('#scheduleForm')[0].reset();
    }

    // Initialize Bootstrap components
    $(document).ready(function() {
        $('.modal').modal({
            show: false
        });
    });

    // Profile Picture Upload
    document.getElementById('profile_pic').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const formData = new FormData();
            formData.append('profile_pic', e.target.files[0]);

            fetch('update_profile_pic.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileImage').src = data.image_path;
                } else {
                    alert('Error uploading image: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error uploading image');
            });
        }
    });

    // Profile Edit Form Submission
    document.getElementById('profileEditForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('update_staff_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Profile updated successfully!');
            } else {
                alert('Error updating profile: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating profile');
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for navigation links
        document.querySelectorAll('.nav a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    // Add offset for fixed header
                    const headerOffset = 80;
                    const elementPosition = targetSection.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth',
                        duration: 1000 // Slower scroll duration
                    });

                    // Update active state
                    document.querySelectorAll('.nav a').forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });

        // Update active nav item on scroll
        window.addEventListener('scroll', function() {
            let scrollPosition = window.scrollY;
            
            // Get all sections
            document.querySelectorAll('section').forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionBottom = sectionTop + section.offsetHeight;
                
                if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                    const currentId = section.getAttribute('id');
                    document.querySelectorAll('.nav a').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${currentId}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        });
    });

    // Add custom smooth scroll function
    function smoothScroll(target, duration) {
        const targetPosition = target.getBoundingClientRect().top;
        const startPosition = window.pageYOffset;
        const distance = targetPosition - 80; // Adjust for header height
        let startTime = null;

        function animation(currentTime) {
            if (startTime === null) startTime = currentTime;
            const timeElapsed = currentTime - startTime;
            const run = ease(timeElapsed, startPosition, distance, duration);
            window.scrollTo(0, run);
            if (timeElapsed < duration) requestAnimationFrame(animation);
        }

        // Easing function for smoother animation
        function ease(t, b, c, d) {
            t /= d / 2;
            if (t < 1) return c / 2 * t * t + b;
            t--;
            return -c / 2 * (t * (t - 2) - 1) + b;
        }

        requestAnimationFrame(animation);
    }

    function updateProfile() {
        const form = document.getElementById('profileForm');
        const formData = new FormData(form);
        const saveBtn = document.querySelector('#editProfileModal .btn-primary');
        const alertPlaceholder = document.getElementById('alertPlaceholder');

        // Show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

        fetch('update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the profile information on the page
                document.getElementById('profile-email').textContent = formData.get('email');
                document.getElementById('profile-phone').textContent = formData.get('mobile_no');
                document.getElementById('profile-address').textContent = formData.get('address');
                document.querySelector('.profile-basic-info h2').textContent = formData.get('full_name');

                // Show success message
                alertPlaceholder.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Profile updated successfully!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;

                // Close modal after delay
                setTimeout(() => {
                    $('#editProfileModal').modal('hide');
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to update profile');
            }
        })
        .catch(error => {
            alertPlaceholder.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${error.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
        })
        .finally(() => {
            // Reset button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = 'Save Changes';
        });
    }

    // Reset form and alerts when modal is closed
    $('#editProfileModal').on('hidden.bs.modal', function () {
        document.getElementById('profileForm').reset();
        document.getElementById('alertPlaceholder').innerHTML = '';
    });
    </script>
</body>
</html>

