<?php
include 'connect.php';

// Get Members Statistics
$members_query = "SELECT COUNT(*) AS total FROM login WHERE role='Member'";
$members_result = mysqli_query($conn, $members_query);
$members_count = mysqli_fetch_assoc($members_result)['total'];

// Get Staff Statistics
$staff_query = "SELECT COUNT(*) AS total FROM login WHERE role='Staff'";
$staff_result = mysqli_query($conn, $staff_query);
$staff_count = mysqli_fetch_assoc($staff_result)['total'];

// Get Payment Statistics
$payment_query = "SELECT 
    COUNT(*) as total_transactions,
    SUM(amount) as total_amount,
    SUM(CASE WHEN status = 'Pending' THEN amount ELSE 0 END) as pending_amount
FROM transactions";
$payment_result = mysqli_query($conn, $payment_query);
$payment_stats = mysqli_fetch_assoc($payment_result);

// Get Product Statistics
$product_query = "SELECT COUNT(*) AS total FROM products";
$product_result = mysqli_query($conn, $product_query);
$product_count = mysqli_fetch_assoc($product_result)['total'];
?>

<!DOCTYPE html>
<html lang="en">
  <head>
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

        .dashboard-container {
          margin-top: 100px;
            padding: 20px;
        }

        .dashboard-section {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .dashboard-section:hover {
            transform: translateY(-5px);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-title {
            font-size: 1.5rem;
            color: #232d39;
            font-weight: 600;
        }

        .stat-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #ed563b;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #232d39;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #777;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .quick-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .action-btn {
            background: #ed563b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .action-btn:hover {
            background: #f9735b;
        }

        .recent-activity {
            margin-top: 20px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .activity-details {
            flex-grow: 1;
        }

        .activity-time {
            color: #777;
            font-size: 0.8rem;
        }

        .chart-container {
            height: 300px;
            margin-top: 20px;
        }
    </style>
  </head>
  <body>
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
                  <li><a href="payments_check.php">Payments</a></li>
                  <li><a href="products.php">Products</a></li>
                  <li class="main-button"><a href="login2.php">Logout</a></li>
                </ul>
            </nav>
          </div>
        </div>
      </div>
    </header>

    <div class="dashboard-container">
        <!-- Overview Section -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <!-- <i class="fas fa-users stat-icon"></i> -->
                    <div class="stat-value"><?php echo $members_count; ?></div>
                    <div class="stat-label">Total Members</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <!-- <i class="fas fa-user-tie stat-icon"></i> -->
                    <div class="stat-value"><?php echo $staff_count; ?></div>
                    <div class="stat-label">Staff Members</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <!-- <i class="fas fa-money-bill-wave stat-icon"></i> -->
                    <div class="stat-value">₹<?php echo number_format($payment_stats['total_amount'] ?? 0); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <!-- <i class="fas fa-dumbbell stat-icon"></i> -->
                    <div class="stat-value"><?php echo $product_count; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
        </div>

        <!-- Members Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Member Management</h2>
                <a href="members.php" class="action-btn">View All Members</a>
            </div>
            <div class="row">
                <!-- Recent Members -->
                <div class="col-md-8">
                    <h4>Recent Members</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Join Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_members_query = "SELECT * FROM Register ORDER BY created_at DESC LIMIT 5";
                                $recent_members_result = mysqli_query($conn, $recent_members_query);
                                while ($member = mysqli_fetch_assoc($recent_members_result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($member['full_name']) . "</td>";
                                    echo "<td>" . date('M d, Y', strtotime($member['created_at'])) . "</td>";
                                    echo "<td><span class='badge bg-success'>Active</span></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Member Stats -->
                <div class="col-md-4">
                    <div class="chart-container">
                        <!-- Add your chart here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Payment Overview</h2>
                <a href="payments_check.php" class="action-btn">View All Payments</a>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="stat-card">
                        <h4>Recent Transactions</h4>
                        <div class="recent-activity">
                            <?php
                            $recent_transactions_query = "SELECT t.*, r.full_name 
                                FROM transactions t 
                                JOIN Register r ON t.user_id = r.user_id 
                                ORDER BY payment_date DESC LIMIT 5";
                            $recent_transactions_result = mysqli_query($conn, $recent_transactions_query);
                            while ($transaction = mysqli_fetch_assoc($recent_transactions_result)) {
                                ?>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                    <div class="activity-details">
                                        <div><?php echo htmlspecialchars($transaction['full_name']); ?></div>
                                        <div class="activity-time">₹<?php echo number_format($transaction['amount'], 2); ?></div>
                                    </div>
                                    <span class="badge bg-<?php echo $transaction['status'] == 'Completed' ? 'success' : 'warning'; ?>">
                                        <?php echo $transaction['status']; ?>
                                    </span>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <!-- Add payment chart here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Staff Management</h2>
                <a href="staff_management.php" class="action-btn">Manage Staff</a>
            </div>
            <!-- Add staff content -->
        </div>

        <!-- Products Section -->
        <div class="dashboard-section">
            <div class="section-header">
                <h2 class="section-title">Product Inventory</h2>
                <a href="products.php" class="action-btn">Manage Products</a>
            </div>
            <!-- Add products content -->
        </div>
    </div>

    <footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <p>Copyright &copy; 2025 FOCUS GYM Admin Panel</p>
          </div>
        </div>
      </div>
    </footer>

    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remove any classes that might interfere with navigation
        document.querySelectorAll('.scroll-to-section').forEach(el => {
            el.classList.remove('scroll-to-section');
        });
    });
    </script>
  </body>
</html>