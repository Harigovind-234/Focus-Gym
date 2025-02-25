<?php
include 'connect.php';
session_start();

// Fetch categories
$categories_query = "SELECT * FROM categories";
$categories_result = mysqli_query($conn, $categories_query);

if (!$categories_result) {
    die("Error fetching categories: " . mysqli_error($conn));
}

// Get product details
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    
    if (!$product) {
        die("Product not found");
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category']);
    
    // Handle new image upload if provided
    if (isset($_FILES['product_image']) && $_FILES['product_image']['size'] > 0) {
        $target_dir = "uploads/products/";
        $file_extension = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
            
            // Update with new image
            $sql = "UPDATE products SET product_name=?, description=?, price=?, category_id=?, image_path=? WHERE product_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssdssi", $product_name, $description, $price, $category_id, $image_path, $product_id);
        }
    } else {
        // Update without changing image
        $sql = "UPDATE products SET product_name=?, description=?, price=?, category_id=? WHERE product_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdii", $product_name, $description, $price, $category_id, $product_id);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: products.php?success=1");
        exit();
    } else {
        $error_message = "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Add your existing CSS here -->
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01" value="<?php echo $product['price']; ?>" required>
            </div>
            
            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                        <option value="<?php echo $category['category_id']; ?>" 
                                <?php echo ($category['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Current Image</label><br>
                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" style="max-width: 200px;">
            </div>
            
            <div class="form-group">
                <label>New Image (leave empty to keep current image)</label>
                <input type="file" name="product_image" class="form-control" accept="image/*">
            </div>
            
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="products.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html> 