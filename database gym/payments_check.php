<?php
session_start();
require_once 'connect.php';

// Check if user is admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     header('Location: index.php');
//     exit;
// }

// Get basic transaction statistics from transactions table
$stats_query = "SELECT 
    SUM(CASE WHEN status = 'Completed' THEN amount ELSE 0 END) as completed_amount,
    SUM(CASE WHEN status = 'Pending' THEN amount ELSE 0 END) as pending_amount,
    SUM(CASE WHEN status = 'Failed' THEN amount ELSE 0 END) as failed_amount,
    SUM(CASE WHEN DATE(payment_date) = CURDATE() THEN amount ELSE 0 END) as today_amount
FROM transactions";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Training Studio - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">
    <style>
        /* Header and Navigation styles */
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

        /* Adjust main content to account for fixed header */
        .dashboard-container {
            margin-top: 100px;
            padding: 20px;
        }

        /* Dashboard styles */
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .amount-text {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .status-badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .header-area .nav .main-button {
          margin-left: 20px;
          display: flex;
          align-items: center;
        }

        .header-area .nav .main-button a {
          background-color: #ed563b;
          color: #fff !important;
          padding: 15px 30px !important;
          border-radius: 5px;
          font-weight: 600;
          font-size: 14px !important;
          text-transform: uppercase;
          transition: all 0.3s ease;
          display: inline-block;
          letter-spacing: 0.5px;
          line-height: 1.4;
          white-space: nowrap;
        }

        .header-area .nav .main-button a:hover {
          background-color: #f9735b;
          color: #fff !important;
          transform: translateY(-2px);
          box-shadow: 0 4px 15px rgba(237, 86, 59, 0.2);
        }

        /* Fix for mobile responsiveness */
        @media (max-width: 991px) {
          .header-area .nav .main-button a {
            padding: 12px 25px !important;
            font-size: 13px !important;
          }
        }

    </style>
</head>
<body>
    <!-- Admin Header -->
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
                            <li><a href="payments_check.php" class="active">Payments</a></li>
                            <li><a href="products.php">Products</a></li>
                            <li class="main-button"><a href="login2.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <div class="dashboard-container">
        <!-- Amount Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted">Today's Transactions</h6>
                    <div class="amount-text">₹<?php echo number_format($stats['today_amount'] ?? 0, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted">Completed Payments</h6>
                    <div class="amount-text text-success">₹<?php echo number_format($stats['completed_amount'] ?? 0, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted">Pending Payments</h6>
                    <div class="amount-text text-warning">₹<?php echo number_format($stats['pending_amount'] ?? 0, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted">Failed Payments</h6>
                    <div class="amount-text text-danger">₹<?php echo number_format($stats['failed_amount'] ?? 0, 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Transaction Search -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" class="form-control" name="start_date" value="<?php echo $_GET['start_date'] ?? ''; ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" class="form-control" name="end_date" value="<?php echo $_GET['end_date'] ?? ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method">
                            <option value="">All Methods</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Transaction History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Build query with filters
                            $query = "SELECT t.*, r.full_name
                                     FROM transactions t 
                                     JOIN Register r ON t.user_id = r.user_id 
                                     WHERE 1=1";

                            if (!empty($_GET['start_date'])) {
                                $query .= " AND payment_date >= '" . mysqli_real_escape_string($conn, $_GET['start_date']) . "'";
                            }
                            if (!empty($_GET['end_date'])) {
                                $query .= " AND payment_date <= '" . mysqli_real_escape_string($conn, $_GET['end_date']) . "'";
                            }
                            if (!empty($_GET['payment_method'])) {
                                $query .= " AND payment_method = '" . mysqli_real_escape_string($conn, $_GET['payment_method']) . "'";
                            }
                            if (!empty($_GET['status'])) {
                                $query .= " AND status = '" . mysqli_real_escape_string($conn, $_GET['status']) . "'";
                            }

                            $query .= " ORDER BY payment_date DESC LIMIT 50";
                            $result = mysqli_query($conn, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $status_class = match($row['status']) {
                                    'Completed' => 'bg-success',
                                    'Pending' => 'bg-warning',
                                    'Failed' => 'bg-danger'
                                };
                                ?>
                                <tr>
                                    <td><?php echo $row['transaction_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['payment_date'])); ?></td>
                                    <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                                    <td><?php echo $row['payment_method']; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo $status_class; ?> text-white">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="viewTransaction(<?php echo $row['transaction_id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($row['status'] == 'Pending'): ?>
                                        <button class="btn btn-sm btn-success" onclick="updateStatus(<?php echo $row['transaction_id']; ?>, 'Completed')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transaction Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="transactionDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewTransaction(id) {
            fetch(`get_transaction.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('transactionDetails').innerHTML = `
                        <div class="mb-3">
                            <strong>Transaction ID:</strong> ${data.transaction_id}
                        </div>
                        <div class="mb-3">
                            <strong>User:</strong> ${data.name}
                        </div>
                        <div class="mb-3">
                            <strong>Amount:</strong> ₹${data.amount}
                        </div>
                        <div class="mb-3">
                            <strong>Date:</strong> ${data.payment_date}
                        </div>
                        <div class="mb-3">
                            <strong>Payment Method:</strong> ${data.payment_method}
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong> ${data.status}
                        </div>
                    `;
                    new bootstrap.Modal(document.getElementById('transactionModal')).show();
                });
        }

        function updateStatus(id, status) {
            if (confirm('Are you sure you want to update this transaction?')) {
                fetch('update_transaction.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        transaction_id: id,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating transaction');
                    }
                });
            }
        }
    </script>
</body>
</html>

