

<!DOCTYPE html>
<html>
<body>
    <h2>Members List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Mobile</th>
            <th>Actions</th>
        </tr>
        <?php
include 'connect.php';

// Error checking for connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// Error checking for query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                <td>
                    <a href="update.php?id=<?php echo urlencode($row['id']); ?>">Edit</a>
                    <a href="delete.php?id=<?php echo urlencode($row['id']); ?>">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- <a href="insert.php">Add</a> -->
    <!-- <br><br> -->
    <!-- <a href="search.php">Search</a> -->
</body>
</html>

<?php
mysqli_close($conn);
?>