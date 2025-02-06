<?php
include 'connect.php';

$id = $_GET['id'];
$sql = "SELECT * FROM employees WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];

    $update_sql = "UPDATE employees SET name='$name', email='$email', position='$position' WHERE id=$id";

    if (mysqli_query($conn, $update_sql)) {
        echo "Record updated successfully";
        header("Location: view.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Update Employee</h2>
    <form method="POST">
        Name: <input type="text" name="name" value="<?php echo $row['name']; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $row['email']; ?>" required><br>
        Position: <input type="text" name="position" value="<?php echo $row['position']; ?>" required><br>
        <button type="submit">Update Employee</button>
    </form>
</body>
</html>
