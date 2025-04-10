<?php
include 'connect.php';
session_start();

// Fetch all products
$query = "SELECT p.*, c.category_name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.category_id 
          ORDER BY p.product_id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching products: " . mysqli_error($conn));
}

// At the top of the file, after database connection
// Fetch categories for the dropdown
$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);

if (!$categories_result) {
    die("Error fetching categories: " . mysqli_error($conn));
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $stock = intval($_POST['stock']);
    
    // Validate inputs
    if (empty($product_name)) {
        $validation_errors[] = "Product name is required";
    }
    if (empty($description)) {
        $validation_errors[] = "Description is required";
    }
    if ($price <= 0) {
        $validation_errors[] = "Price must be greater than 0";
    }
    if ($stock < 0) {
        $validation_errors[] = "Stock cannot be negative";
    }
    
    if (empty($validation_errors)) {
        // Handle file upload
        $image_path = '';
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $target_dir = "uploads/products/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            }
        }

        // Prepare and execute the SQL statement
        $sql = "INSERT INTO products (product_name, description, price, category_id, image_path, stock) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            die("Error preparing statement: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssdssi", $product_name, $description, $price, $category, $image_path, $stock);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Product added successfully!";
            // Refresh the product list
            $result = mysqli_query($conn, $query);
            if (!$result) {
                die("Error refreshing products: " . mysqli_error($conn));
            }
        } else {
            $error_message = "Error adding product: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
}

// Update the orders query section
$orders_query = "SELECT o.order_id, o.user_id, o.product_id, o.quantity, 
                        o.order_date, o.total_price, o.status,
                        o.payment_method, o.payment_status,
                        o.collection_time, o.collection_status,
                        o.created_at,
                        p.product_name, p.price,
                        r.full_name, r.mobile_no, r.address
                 FROM orders o
                 INNER JOIN products p ON o.product_id = p.product_id
                 INNER JOIN register r ON o.user_id = r.user_id
                 ORDER BY o.created_at DESC";

$orders_result = mysqli_query($conn, $orders_query);

// Add error debugging
if (!$orders_result) {
    echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
}
?>

<?php
// Add this debugging section at the bottom of your file
if (isset($error_message)) {
    echo "<div class='alert alert-danger'>Debug: $error_message</div>";
}

// Debug product query
echo "<div style='display:none;'>";
echo "Number of products found: " . mysqli_num_rows($result) . "<br>";
echo "SQL Query: $query<br>";
if (!$result) {
    echo "Query Error: " . mysqli_error($conn) . "<br>";
}
echo "</div>";

// Add this helper function for status colors
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'warning';
        case 'approved':
            return 'info';
        case 'completed':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}

// Add this helper function for payment status colors
function getPaymentStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'warning';
        case 'paid':
            return 'success';
        case 'refunded':
            return 'danger';
        default:
            return 'secondary';
    }
}

// Add this helper function for collection status colors
function getCollectionStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending':
            return 'warning';
        case 'collected':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
          margin: 100px auto 0;
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

        /* Main content spacing */
        .main-content {
            margin: 100px auto 30px;
            padding: 0 30px;
            max-width: 1400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 40px; /* Increased gap between main sections */
        }
        
        .product-list {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        /* Orders List Styling */
        .orders-list {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 30px;
        }

        .orders-list h3 {
            color: #232d39;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ed563b;
        }

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-bottom: 0;
        }

        .table thead th {
            background: #232d39;
            color: white;
            padding: 15px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
            border: none;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }

        .table tbody td {
            padding: 15px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .table tbody tr:hover td {
            background: #f2f2f2;
            transform: scale(1.01);
        }

        .table tbody td:first-child {
            border-left: 1px solid #dee2e6;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .table tbody td:last-child {
            border-right: 1px solid #dee2e6;
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        /* Badge Styling */
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        .bg-warning {
            background-color: #ffeeba !important;
            color: #856404 !important;
        }

        .bg-success {
            background-color: #d4edda !important;
            color: #155724 !important;
        }

        .bg-danger {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }

        .bg-info {
            background-color: #d1ecf1 !important;
            color: #0c5460 !important;
        }

        /* Action Buttons */
        .btn-group {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: nowrap;
        }

        .btn-group .btn {
            padding: 6px 12px;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: #ed563b;
            border-color: #ed563b;
        }

        .btn-primary:hover {
            background-color: #f9735b;
            border-color: #f9735b;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(237, 86, 59, 0.2);
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
        }

        /* Center the container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
            width: 100%;
        }

        /* Adjust responsive styles */
        @media (max-width: 1200px) {
            .main-content,
            .container,
            .admin-dashboard {
                padding: 0 20px;
            }
        }

        @media (max-width: 768px) {
            .main-content,
            .container,
            .admin-dashboard {
                padding: 0 15px;
            }
            
            .product-form,
            .product-list,
            .orders-list {
                padding: 20px;
            }

            .table-responsive {
                margin: 0;
            }
        }

        /* Individual section spacing */
        .product-form,
        .product-list,
        .orders-list {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px; /* Increased padding */
            margin-bottom: 0;
        }

        /* Section headers spacing */
        .product-form h3,
        .product-list h3,
        .orders-list h3 {
            color: #232d39;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 30px; /* Increased margin */
            padding-bottom: 15px;
            border-bottom: 2px solid #ed563b;
        }

        /* Form groups spacing */
        .form-group {
            margin-bottom: 25px; /* Increased margin between form elements */
        }

        /* Table container spacing */
        .table-responsive {
            margin-top: 20px;
        }

        /* Alert messages spacing */
        .alert {
            margin-bottom: 30px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .main-content {
                gap: 30px; /* Slightly reduced gap on mobile */
                padding: 0 20px;
            }

            .product-form,
            .product-list,
            .orders-list {
                padding: 20px;
            }
        }

        /* Improve spacing and alignment */
        .main-content {
            margin: 100px auto 30px;
            padding: 0 30px;
            max-width: 1400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Consistent card styling */
        .product-form,
        .product-list,
        .orders-list {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 0; /* Remove bottom margin since we're using gap */
        }

        /* Enhance table actions */
        .btn-group {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: nowrap;
        }

        .btn-group .btn {
            padding: 6px 12px;
            white-space: nowrap;
        }

        /* Status badge enhancements */
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        /* Add approval button styling */
        .btn-approve {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-approve:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        /* Improve table cell alignment */
        .table td {
            vertical-align: middle !important;
        }

        .table td .small {
            margin-top: 4px;
        }

        /* Add these styles to your <style> section */
        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .action-btn {
            background: #ffc107;
            color: #856404;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #ffdb4d;
        }

        .action-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 4px;
            z-index: 1;
        }

        .action-dropdown-content a {
            color: #232d39;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .action-dropdown-content a:hover {
            background-color: #f8f9fa;
        }

        .action-dropdown:hover .action-dropdown-content {
            display: block;
        }

        .status-badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.pending {
            background: #ffeeba;
            color: #856404;
        }

        .status-badge.paid {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* Add these styles to your <style> section */
        .payment-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
            align-items: flex-start;
        }

        .payment-method {
            font-size: 12px;
            padding: 2px 8px;
            background: #f8f9fa;
            border-radius: 12px;
            color: #6c757d;
        }

        .payment-method small {
            font-weight: 500;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .status-badge.pending {
            background: #ffeeba;
            color: #856404;
        }

        .status-badge.paid {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            margin-top: 5px;
            background: #ffc107;
            color: #856404;
            border: none;
            padding: 4px 12px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: #ffdb4d;
        }

        .action-dropdown-content {
            min-width: 140px;
            right: -10px;
            top: 100%;
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

        @media (max-width: 1200px) {
            .members-container {
                padding: 0 20px;
            }
        }

        @media (max-width: 768px) {
            .members-container {
                padding: 0 15px;
                margin-top: 90px;
            }
            
            .members-card {
                padding: 15px;
            }
        }


    </style>
</head>
<body>
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
                        <li><a href="products.php" class="active">Products</a></li>
                        <li class="main-button"><a href="login2.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
<section class="main-content">
    <div class="main-content">
        <div class="container">
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <div class="product-form">
                <h3>Add New Product</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select a category</option>
                            <?php 
                            // Reset the categories result pointer
                            mysqli_data_seek($categories_result, 0);
                            while ($category = mysqli_fetch_assoc($categories_result)): 
                            ?>
                                <option value="<?php echo $category['category_id']; ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Product Image</label>
                        <input type="file" name="product_image" class="form-control" accept="image/*" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock Quantity*</label>
                        <input type="number" class="form-control" id="stock" name="stock" required min="0" value="0">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>

            <div class="product-list">
                <h3>Product List</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)): 
                            ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" 
                                             class="product-image" 
                                             alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                                    </td>
                                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                    <td>₹<?php echo number_format($row['price'], 2); ?></td>
                                    <td><?php echo $row['stock']; ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td>
                                        <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" 
                                           class="btn btn-sm btn-primary">Edit</a>
                                        <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    </td>
                                </tr>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                                <tr>
                                    <td colspan="6" class="text-center">No products found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="orders-list">
                <h3>Orders</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Collection</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($orders_result && mysqli_num_rows($orders_result) > 0): ?>
                                <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
                                    <tr>
                                        <td>
                                            #<?php echo htmlspecialchars($order['order_id']); ?>
                                            <div class="small text-muted">
                                                <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($order['full_name']); ?>
                                            <div class="small text-muted">
                                                <?php echo htmlspecialchars($order['mobile_no']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            ₹<?php echo number_format($order['total_price'], 2); ?>
                                            <div class="small text-muted">
                                                Qty: <?php echo $order['quantity']; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="payment-info">
                                                <span class="status-badge <?php echo strtolower($order['payment_status']); ?>">
                                                    <?php echo ucfirst($order['payment_status']); ?>
                                                </span>
                                                <div class="payment-method">
                                                    <small class="text-muted">
                                                        <?php echo ucfirst($order['payment_method']); ?>
                                                    </small>
                                                </div>
                                                <?php if ($order['payment_status'] === 'pending'): ?>
                                                    <?php if ($order['payment_method'] === 'cash'): ?>
                                                        <div class="action-dropdown">
                                                            <button class="action-btn">
                                                                <i class="fas fa-check"></i> Approve
                                                            </button>
                                                            <div class="action-dropdown-content">
                                                                <a href="#" class="approve-payment" data-order-id="<?php echo $order['order_id']; ?>">
                                                                    Confirm Cash Received
                                                                </a>
                                                                <a href="#" class="cancel-payment" data-order-id="<?php echo $order['order_id']; ?>">
                                                                    Cancel Payment
                                                                </a>
                                                            </div>
                                                        </div>
                                                    <?php elseif ($order['payment_method'] === 'razorpay'): ?>
                                                        <button class="btn btn-primary btn-sm mt-2 pay-now-btn" 
                                                                onclick="initiateRazorpayPayment(<?php echo $order['order_id']; ?>, <?php echo $order['total_price']; ?>, '<?php echo $order['razorpay_order_id']; ?>')">
                                                            Pay Now
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($order['collection_time']): ?>
                                                <div><?php echo date('d/m/Y H:i', strtotime($order['collection_time'])); ?></div>
                                            <?php endif; ?>
                                            <span class="badge bg-<?php echo getCollectionStatusColor($order['collection_status']); ?>">
                                                <?php echo ucfirst($order['collection_status'] ?? 'pending'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo getStatusColor($order['status']); ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No orders found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all form elements
        const form = document.querySelector('form');
        const productName = document.querySelector('input[name="product_name"]');
        const price = document.querySelector('input[name="price"]');
        const category = document.querySelector('select[name="category"]');
        const description = document.querySelector('textarea[name="description"]');
        const productImage = document.querySelector('input[name="product_image"]');
        const stockInput = document.getElementById('stock');

        // Add validation styles
        const addValidationStyles = (element, isValid, message) => {
            element.classList.remove('is-valid', 'is-invalid');
            element.classList.add(isValid ? 'is-valid' : 'is-invalid');
            
            // Get or create feedback div
            let feedback = element.nextElementSibling;
            if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                element.parentNode.appendChild(feedback);
            }
            feedback.textContent = message;
        };

        // Product Name Validation
        productName.addEventListener('input', function() {
            const isValid = this.value.length >= 3;
            addValidationStyles(this, isValid, 'Product name must be at least 3 characters long');
        });

        // Price Validation
        price.addEventListener('input', function() {
            const priceValue = parseFloat(this.value);
            const isValid = !isNaN(priceValue) && priceValue > 0;
            addValidationStyles(this, isValid, 'Price must be greater than 0');
        });

        // Category Validation
        category.addEventListener('change', function() {
            const isValid = this.value !== '';
            addValidationStyles(this, isValid, 'Please select a category');
        });

        // Description Validation
        description.addEventListener('input', function() {
            const isValid = this.value.length >= 10;
            addValidationStyles(this, isValid, 'Description must be at least 10 characters long');
        });

        // Image Validation
        productImage.addEventListener('change', function() {
            const file = this.files[0];
            let isValid = true;
            let message = '';

            if (file) {
                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif','image/webp'];
                if (!validTypes.includes(file.type)) {
                    isValid = false;
                    message = 'Please select a valid image file (JPEG, PNG, or GIF)';
                }
                // Check file size (max 5MB)
                else if (file.size > 5 * 1024 * 1024) {
                    isValid = false;
                    message = 'File size must be less than 5MB';
                }
            } else {
                isValid = false;
                message = 'Please select an image file';
            }

            addValidationStyles(this, isValid, message);
        });

        // Stock Validation
        stockInput.addEventListener('input', function() {
            const stockValue = parseInt(this.value);
            const isValid = !isNaN(stockValue) && stockValue >= 0;
            if (this.value < 0) {
                this.value = 0;
            }
            addValidationStyles(this, isValid, 'Stock must be 0 or greater');
        });

        // Form Submission Validation
        form.addEventListener('submit', function(event) {
            // Check all fields
            const fields = [productName, price, category, description, productImage, stockInput];
            let isValid = true;

            fields.forEach(field => {
                if (!field.value) {
                    isValid = false;
                    addValidationStyles(field, false, 'This field is required');
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please fill in all required fields correctly');
            }
        });

        // Handle approve payment
        document.querySelectorAll('.approve-payment').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const orderId = this.dataset.orderId;
                
                if (confirm('Confirm cash payment received for Order #' + orderId + '?')) {
                    try {
                        const response = await fetch('approve_payment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                orderId: orderId,
                                action: 'approve_payment'
                            })
                        });
                        
                        if (response.ok) {
                            location.reload();
                        } else {
                            throw new Error('Failed to approve payment');
                        }
                    } catch (error) {
                        alert('Error: ' + error.message);
                    }
                }
            });
        });

        // Handle Razorpay payment verification
        function verifyRazorpayPayment(paymentData, orderId) {
            return fetch('verify_payment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    razorpay_payment_id: paymentData.razorpay_payment_id,
                    razorpay_order_id: paymentData.razorpay_order_id,
                    razorpay_signature: paymentData.razorpay_signature,
                    order_id: orderId
                })
            }).then(response => response.json());
        }

        // Update payment status after Razorpay payment
        window.updatePaymentStatus = function(paymentData, orderId) {
            verifyRazorpayPayment(paymentData, orderId)
                .then(response => {
                    if (response.status === 'success') {
                        alert('Payment successful!');
                        location.reload();
                    } else {
                        throw new Error(response.message || 'Payment verification failed');
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
        };

        // Handle cancel payment
        document.querySelectorAll('.cancel-payment').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const orderId = this.dataset.orderId;
                
                if (confirm('Are you sure you want to cancel this payment?')) {
                    try {
                        const response = await fetch('approve_payment.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                orderId: orderId,
                                action: 'cancel_payment'
                            })
                        });
                        
                        if (response.ok) {
                            location.reload();
                        } else {
                            throw new Error('Failed to cancel payment');
                        }
                    } catch (error) {
                        alert('Error: ' + error.message);
                    }
                }
            });
        });
    });

    function initiateRazorpayPayment(orderId, amount, razorpayOrderId) {
        var options = {
            "key": "rzp_test_Fur0pLo5d2MztK",
            "amount": amount * 100, // Amount in paise
            "currency": "INR",
            "name": "Focus Gym",
            "description": "Order #" + orderId,
            "order_id": razorpayOrderId,
            "handler": function (response) {
                // Call our payment verification endpoint
                updatePaymentStatus(response, orderId);
            },
            "prefill": {
                "name": "<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>",
                "email": "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>",
                "contact": "<?php echo isset($_SESSION['mobile_no']) ? $_SESSION['mobile_no'] : ''; ?>"
            },
            "theme": {
                "color": "#ed563b"
            }
        };
        
        var rzp = new Razorpay(options);
        rzp.open();
    }
    </script>
</body>
</html>

