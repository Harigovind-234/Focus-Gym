<?php
include 'connect.php';

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Handle POST request for status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $update_sql = "UPDATE Register SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $id);
    
    $response = ['success' => $stmt->execute()];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Get member details
$sql = "SELECT * FROM Register WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: dashboard.php');
    exit();
}

$member = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details - Training Studio</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }

        .member-details-card {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .member-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .member-title {
            color: #232d39;
            margin: 0;
            font-size: 24px;
        }

        .member-status {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            color: white;
            background-color: #4CAF50;
        }

        .member-status.blocked {
            background-color: #dc3545;
        }

        .member-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .info-label {
            color: #7a7a7a;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #232d39;
            font-weight: 500;
            font-size: 16px;
        }

        .block-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 14px;
        }

        .block-button:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
        .member-status {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    color: white;
    background-color: #4CAF50;
    transition: all 0.3s ease;
}

.member-status.blocked {
    background-color: #dc3545 !important;
    color: white !important;
}
    </style>
</head>
<body>
    <div class="member-details-card">
    <div class="member-header">
    <h1 class="member-title">Member Details</h1>
    <span class="member-status <?php echo $member['status'] === 'Blocked' ? 'blocked' : ''; ?>" id="memberStatus">
        <?php echo htmlspecialchars($member['status'] ?? 'Active'); ?>
    </span>
    <button id="blockButton" class="block-button" <?php echo $member['status'] === 'Blocked' ? 'disabled' : ''; ?>>
        Block Member
    </button>
</div>

        <div class="member-info">
            <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value"><?php echo htmlspecialchars($member['fullname']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value"><?php echo htmlspecialchars($member['email']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Address</div>
                <div class="info-value"><?php echo htmlspecialchars($member['address']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Mobile</div>
                <div class="info-value"><?php echo htmlspecialchars($member['mobile']); ?></div>
            </div>
        </div>
    </div>

    <script>document.getElementById('blockButton').addEventListener('click', function() {
    fetch(window.location.href, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ status: 'Blocked' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const statusElement = document.getElementById('memberStatus');
            statusElement.textContent = 'Blocked';
            statusElement.classList.add('blocked');
            this.disabled = true;
        }
    })
    .catch(error => console.error('Error:', error));
});

</script>
</body>
</html>