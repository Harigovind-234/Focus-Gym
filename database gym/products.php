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
    
    // Handle file upload
    if (isset($_FILES['product_image'])) {
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

    $sql = "INSERT INTO products (product_name, description, price, category_id, image_path) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssdss", $product_name, $description, $price, $category, $image_path);
    
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

        .product-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
        
        .main-content {
            margin-top: 80px;
            margin-left: 20%;
            padding: 20px;
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
        </div>
    </div>

    <script src="assets/js/jquery-2.1.0.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>

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
?> 