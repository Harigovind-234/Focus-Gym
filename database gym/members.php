<?php
session_start();
require_once 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Members - Focus Gym</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">
    
    <style>
        body {
            background-color: #f4f6f9;
        }

        .members-container {
            margin-left: 20%;
            margin-top: 100px;
            padding: 20px;
        }

        .members-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .members-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ed563b;
        }

        .members-title {
            color: #232d39;
            font-size: 24px;
            font-weight: 600;
        }

        .members-count {
            background: #ed563b;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
        }

        .members-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .members-table th {
            background-color: #232d39;
            color: white;
            padding: 15px;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .members-table tr {
            transition: all 0.3s ease;
        }

        .members-table td {
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }

        .members-table tr:hover td {
            background-color: #f2f2f2;
            transform: scale(1.01);
        }

        .members-table td:first-child {
            border-left: 1px solid #dee2e6;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .members-table td:last-child {
            border-right: 1px solid #dee2e6;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .view-btn {
            background-color: #ed563b;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .view-btn:hover {
            background-color: #f9735b;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .search-box {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .search-btn {
            background-color: #232d39;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background-color: #ed563b;
        }

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
            color: #fff;
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
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <a href="admin.php" class="logo">Admin <em>Panel</em></a>
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

    <div class="members-container">
        <div class="members-card">
            <div class="members-header">
                <h2 class="members-title">Gym Members</h2>
                <?php
                $count_query = "SELECT COUNT(*) as count FROM login WHERE role = 'Member'";
                $count_result = mysqli_query($conn, $count_query);
                $count = mysqli_fetch_assoc($count_result)['count'];
                ?>
                <span class="members-count">Total Members: <?php echo $count; ?></span>
            </div>

            <div class="search-box">
                <form method="GET" action="" style="width: 100%; display: flex; gap: 10px;">
                    <input type="text" name="search" class="search-input" placeholder="Search by name or email..." 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit" class="search-btn">Search</button>
                </form>
            </div>

            <table class="members-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Join Date</th>
                        <th>Mobile</th>
                        <th>Action</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     include 'connect.php';
                     if (!$conn) {
                         die("Connection failed: " . mysqli_connect_error());
                     }
                     
                     $sql = "SELECT register.*, login.role,login.email FROM register INNER JOIN login ON register.user_id = login.user_id WHERE login.role = 'Member' ORDER BY register.created_at DESC";
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
                         echo "<td>";
                         echo "<div class='action-buttons'>";
                         echo "<a href='admin-profile-details.php?id=" . $row['user_id'] . "' class='view-btn'>View</a>";
                         echo "<form method='POST' action='update_role.php' style='display:inline;'>";
                         echo "<input type='hidden' name='user_id' value='" . $row['user_id'] . "'>";
                         echo "<input type='hidden' name='email' value='" . $row['email'] . "'>";
                         echo "<input type='hidden' name='name' value='" . $row['full_name'] . "'>";
                         echo "<button type='submit' name='make_staff' class='staff-btn'>Make Staff</button>";
                         echo "</form>";
                         echo "</div>";
                         echo "</td>";
                         echo "<td>";
                         if ($row['role'] == 'Member') {
                             echo "<form method='POST' action='update_role.php' style='display:inline;'>";
                             echo "<input type='hidden' name='user_id' value='" . $row['user_id'] . "'>";
                             echo "<button type='submit' name='remove_staff' class='btn btn-danger btn-sm'>Remove Staff</button>";
                             echo "</form>";
                         } else if ($row['role'] == 'staff') {
                             echo "<form method='POST' action='update_role.php' style='display:inline;'>";
                             echo "<input type='hidden' name='user_id' value='" . $row['user_id'] . "'>";
                             echo "<button type='submit' name='remove_staff' class='btn btn-danger btn-sm'>Remove Staff</button>";
                             echo "</form>";
                         }
                         echo "</td>";
                         echo "</tr>";
                     }
                     ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
    document.querySelector('.search-input').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
    </script>
</body>
</html> 