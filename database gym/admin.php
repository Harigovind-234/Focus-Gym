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
          background-color: #232d39;
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
          color: #fff;
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
          color: #7a7a7a;
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
    </style>
  </head>
  <body>
    <header class="header-area header-sticky">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <nav class="main-nav">
              <a href="index.html" class="logo">Admin <em>Panel</em></a>
              <ul class="nav">
                <li class="scroll-to-section"><a href="#dashboard" class="active">Dashboard</a></li>
                <li class="scroll-to-section"><a href="#members">Members</a></li>
                <li class="scroll-to-section"><a href="#classes">Staff</a></li>
                <li class="scroll-to-section"><a href="#reports">echart</a></li>
                <li class="main-button"><a href="location:enhanced-gym-landing.php">Logout</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </header>

    <div class="admin-dashboard">
      <div class="admin-card">
        <h3>Dashboard Overview</h3>
        <div class="admin-stats">
          <div class="admin-stat-item">
            <div class="admin-stat-value">342</div>
            <div class="admin-stat-label">Total Members</div>
          </div>
          <div class="admin-stat-item">
            <div class="admin-stat-value">24</div>
            <div class="admin-stat-label">Active Classes</div>
          </div>
          <div class="admin-stat-item">
            <div class="admin-stat-value">$45,678</div>
            <div class="admin-stat-label">Monthly Revenue</div>
          </div>
          <div class="admin-stat-item">
            <div class="admin-stat-value">12</div>
            <div class="admin-stat-label">New Memberships</div>
          </div>
        </div>
      </div>

      <div class="admin-card">
        <h3> Gym Members </h3>
        <table class="admin-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Address</th>
              <!-- <th>Membership Type</th> -->
              <th>Join Date</th>
              <th>Mobile</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include 'connect.php';
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            
            $sql = "SELECT * FROM Register ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);
            
            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                // echo "<td>" . htmlspecialchars($row['membership_type']) . "</td>";
                echo "<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                echo "<td>" . htmlspecialchars($row['mobile']) . "</td>";
                echo "<td>
                        <a href='admin-profile-details.php?id=" . $row['id'] . "' class='admin-button view-details'>View</a>
                      </td>";
                echo "</tr>";
            }
            ?>
          </tbody>
        </table>
        <div class="admin-actions">
          <button class="admin-button">View All Members</button>
        </div>
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
    <script src="assets/js/custom.js"></script>
  </body>
</html>