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
    $stock = intval($_POST['stock']);
    
    // Handle new image upload if provided
    if (isset($_FILES['product_image']) && $_FILES['product_image']['size'] > 0) {
        $target_dir = "uploads/products/";
        $file_extension = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
            
            // Update with new image
            $sql = "UPDATE products SET product_name=?, description=?, price=?, category_id=?, image_path=?, stock=? WHERE product_id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssdssii", $product_name, $description, $price, $category_id, $image_path, $stock, $product_id);
        }
    } else {
        // Update without changing image
        $sql = "UPDATE products SET product_name=?, description=?, price=?, category_id=?, stock=? WHERE product_id=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdiii", $product_name, $description, $price, $category_id, $stock, $product_id);
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
    <style>
        .is-valid {
            border-color: #28a745 !important;
            padding-right: calc(1.5em + 0.75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + 0.75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 80%;
        }

        .is-invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form method="POST" enctype="multipart/form-data" id="editProductForm" novalidate>
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            
            <div class="form-group mb-3">
                <label>Product Name</label>
                <input type="text" name="product_name" class="form-control" 
                       value="<?php echo htmlspecialchars($product['product_name']); ?>" required 
                       minlength="3">
                <div class="invalid-feedback">Product name must be at least 3 characters long</div>
            </div>
            
            <div class="form-group mb-3">
                <label>Price</label>
                <input type="number" name="price" class="form-control" step="0.01" 
                       value="<?php echo $product['price']; ?>" required min="0">
                <div class="invalid-feedback">Price must be greater than 0</div>
            </div>
            
            <div class="form-group mb-3">
                <label>Stock</label>
                <input type="number" name="stock" class="form-control" 
                       value="<?php echo $product['stock']; ?>" required min="0">
                <div class="invalid-feedback">Stock cannot be negative</div>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editProductForm');
        const inputs = form.querySelectorAll('input, textarea, select');

        // Validation function
        function validateInput(input) {
            let isValid = true;
            let message = '';

            if (input.type === 'text' && input.name === 'product_name') {
                isValid = input.value.length >= 3;
                message = 'Product name must be at least 3 characters long';
            }
            else if (input.type === 'number' && input.name === 'price') {
                isValid = parseFloat(input.value) > 0;
                message = 'Price must be greater than 0';
            }
            else if (input.type === 'number' && input.name === 'stock') {
                isValid = parseInt(input.value) >= 0;
                message = 'Stock cannot be negative';
            }
            else if (input.tagName === 'TEXTAREA') {
                isValid = input.value.length >= 10;
                message = 'Description must be at least 10 characters long';
            }
            else if (input.type === 'file' && input.files.length > 0) {
                const file = input.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                isValid = validTypes.includes(file.type) && file.size <= 5 * 1024 * 1024;
                message = !validTypes.includes(file.type) ? 
                         'Please select a valid image file (JPEG, PNG, or GIF)' : 
                         'File size must be less than 5MB';
            }

            input.classList.toggle('is-valid', isValid);
            input.classList.toggle('is-invalid', !isValid);

            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = message;
            }

            return isValid;
        }

        // Add validation on input
        inputs.forEach(input => {
            input.addEventListener('input', () => validateInput(input));
            input.addEventListener('blur', () => validateInput(input));
        });

        // Form submission
        form.addEventListener('submit', function(event) {
            let isValid = true;

            inputs.forEach(input => {
                if (input.type !== 'file' || input.files.length > 0) { // Skip empty file input
                    if (!validateInput(input)) {
                        isValid = false;
                    }
                }
            });

            if (!isValid) {
                event.preventDefault();
                alert('Please check the form for errors');
            }
        });
    });
    </script>
</body>
</html> 