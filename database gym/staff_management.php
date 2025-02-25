<?php
session_start();
require_once 'connect.php';

// Check if user is admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header('Location: staff_management.php');
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Staff Management - Focus Gym</title>
    
    <!-- Include your CSS files -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Training Studio - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">
    <style>
        .header-area {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          background: #232d39 !important;
          box-shadow: 0 2px 10px rgba(0,0,0,0.1);
          z-index: 1000;
        }

        

        .header-area .container {
          max-width: 1200px;
          margin: 0 auto;
          padding: 0 15px;
        }

        .header-area .main-nav {
          display: flex;
          justify-content: space-between;
          align-items: center;
          height: 80px;
        }

        .header-area .logo {
          color: #ed563b;
          font-size: 24px;
          font-weight: 700;
          text-decoration: none;
          letter-spacing: 0.5px;
        }

        .header-area .logo em {
          color: #fff;
          font-style: normal;
          font-weight: 300;
        }

        .header-area .nav {
          display: flex;
          align-items: center;
          list-style: none;
          margin: 0;
          padding: 0;
        }

        .header-area .nav li {
          margin-left: 25px;
        }

        .header-area .nav li a {
          text-decoration: none;
          text-transform: uppercase;
          font-size: 13px;
          font-weight: 500;
          transition: color 0.3s ease;
        }

        .header-area .nav li a:hover,
        .header-area .nav li a.active {
          color: #ed563b;
        }

        .header-area .nav .main-button a {
          display: inline-block;
          background-color: #ed563b;
          color: #fff;
          padding: 10px 20px;
          border-radius: 5px;
          transition: background-color 0.3s ease;
        }

        .header-area .nav .main-button a:hover {
          background-color: #f9735b;
        }

        .admin-dashboard {
          max-width: 1200px;
          margin-left: 20%;
          margin-top: 100px;
          padding: 0 15px;
        }

        .admin-card {
          background-color: #fff;
          border-radius: 5px;
          box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
          padding: 30px;
          margin-bottom: 30px;
        }

        .admin-card h3 {
          color: #232d39;
          margin-bottom: 20px;
          font-size: 23px;
          letter-spacing: 0.5px;
        }

        .admin-stats {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 20px;
        }

        .admin-stat-item {
          background-color: #f7f7f7;
          border-radius: 5px;
          padding: 20px;
          text-align: center;
        }

        .admin-stat-value {
          font-size: 36px;
          color: #ed563b;
          font-weight: 700;
        }

        .admin-stat-label {
          color:rgb(17, 15, 15);
          text-transform: uppercase;
          font-size: 13px;
        }

        .admin-table {
          width: 100%;
          border-collapse: collapse;
        }

        .admin-table th {
          background-color: #ed563b;
          color: white;
          padding: 12px;
          text-align: left;
          text-transform: uppercase;
          font-size: 13px;
        }

        .admin-table td {
          padding: 12px;
          border-bottom: 1px solid #eee;
          color: #232d39;
        }

        .admin-actions {
          display: flex;
          justify-content: space-between;
          margin-top: 20px;
        }

        .admin-button {
          display: inline-block;
          font-size: 13px;
          padding: 11px 17px;
          background-color: #ed563b;
          color: #fff;
          text-align: center;
          font-weight: 400;
          text-transform: uppercase;
          transition: all 0.3s;
          border: none;
          border-radius: 5px;
          cursor: pointer;
        }

        .admin-button:hover {
          background-color: #f9735b;
        }

        .view-details {
          background-color: #f9735b;
        }

        .view-details:hover {
          background-color: #f9735b;
        }

        .nav li a {
            cursor: pointer;
            color: #fff;
            text-decoration: none;
        }

        .nav li {
            list-style: none;
            margin: 0 15px;
        }

        .nav li a:hover {
            color: #ed563b;
        }

        /* Remove any interfering styles */
        .scroll-to-section {
            all: unset;
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
                        <a href="admin.php" class="logo">Admin<em> Panel</em></a>
                        <ul class="nav">
                          <li><a href="admin.php">Home</a></li>
                          <li><a href="members.php">Members</a></li>
                          <li><a href="staff_management.php">Staff</a></li>
                          <li><a href="Payments_check.php">Payments</a></li>
                          <li><a href="products.php">Products</a></li>
                          <li class="main-button"><a href="login2.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <section class="section" id="staff-section">
        <div class="container">
            <div class="section-heading">
                <h2>Staff <em>Management</em></h2>
                <img src="assets/images/line-dec.png" alt="">
            </div>

            <div class="admin-card">
                <h3>Gym Staffs</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Join Date</th>
                            <th>Mobile</th>
                            <th>Role</th>
                            <th>Action</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }
                        
                        $sql = "SELECT register.*, login.role, login.email 
                               FROM register 
                               INNER JOIN login ON register.user_id = login.user_id where role = 'staff'
                               ORDER BY register.created_at DESC";
                        $result = mysqli_query($conn, $sql);
                        
                        if (!$result) {
                            die("Query failed: " . mysqli_error($conn));
                        }
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row['mobile_no']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                            echo "<td><a href='admin-profile-details.php?id=" . $row['user_id'] . "' class='admin-button view-details'>View</a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="admin-card">
                <h3>Staff Member Assignments</h3>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Email</th>
                            <th>Total Members</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $staff_query = "SELECT r.*, l.email, 
                               (SELECT COUNT(*) FROM StaffAssignedMembers WHERE trainer_id = r.user_id) as member_count
                               FROM register r 
                               INNER JOIN login l ON r.user_id = l.user_id 
                               WHERE l.role = 'staff'";
                        $staff_result = mysqli_query($conn, $staff_query);
                        
                        while ($staff = mysqli_fetch_assoc($staff_result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($staff['full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($staff['email']) . "</td>";
                            echo "<td>" . $staff['member_count'] . " members</td>";
                            echo "<td>
                                    <a href='assign_members.php?staff_id=" . $staff['user_id'] . "' class='admin-button btn-sm'>
                                        Assign Members
                                    </a>
                                    <button class='admin-button view-details btn-sm' onclick='viewAssignedMembers(" . $staff['user_id'] . ", \"" . htmlspecialchars($staff['full_name']) . "\")'>
                                        View Members
                                    </button>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Add View Members Modal -->
    <div class="modal fade" id="viewMembersModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Members Assigned to <span id="staffName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="assignedMembersList">
                </div>
            </div>
        </div>
    </div>

    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright © 2024 Focus Gym - All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Add this JavaScript -->
    <script>
    function viewAssignedMembers(staffId, staffName) {
        $('#staffName').text(staffName);
        $.ajax({
            url: 'get_assigned_members.php',
            type: 'GET',
            data: { staff_id: staffId },
            success: function(response) {
                $('#assignedMembersList').html(response);
                new bootstrap.Modal(document.getElementById('viewMembersModal')).show();
            },
            error: function() {
                alert('Error fetching assigned members');
            }
        });
    }
    </script>
</body>
</html> 