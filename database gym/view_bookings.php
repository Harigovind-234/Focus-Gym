<?php
session_start();
include "connect.php";

// Check if user is logged in as staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: login2.php");
    exit();
}

$trainer_id = $_SESSION['user_id'];
$today = date('Y-m-d');

// Get selected date and session
$selected_date = isset($_GET['date']) ? $_GET['date'] : $today;
$selected_session = isset($_GET['session']) ? $_GET['session'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - FOCUS GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center mb-0">Session Bookings</h3>
                    </div>
                    <div class="card-body">
                        <!-- Date Selection -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Select Date</h5>
                            </div>
                            <div class="card-body">
                                <form method="GET" class="d-flex gap-3">
                                    <input type="date" name="date" value="<?php echo $selected_date; ?>" 
                                           class="form-control" required 
                                           min="<?php echo date('Y-m-d'); ?>"
                                           onchange="validateDate(this)"
                                           id="datePicker">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-calendar-alt"></i> Select Date
                                    </button>
                                </form>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Weekends (Saturday & Sunday) are not available for booking
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Session Buttons -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <a href="?date=<?php echo $selected_date; ?>&session=morning" 
                                   class="btn btn-lg <?php echo $selected_session === 'morning' ? 'btn-warning' : 'btn-outline-warning'; ?> me-3">
                                    <i class="fas fa-sun"></i> Morning Session
                                </a>
                                <a href="?date=<?php echo $selected_date; ?>&session=evening" 
                                   class="btn btn-lg <?php echo $selected_session === 'evening' ? 'btn-info' : 'btn-outline-info'; ?>">
                                    <i class="fas fa-moon"></i> Evening Session
                                </a>
                            </div>
                        </div>

                        <!-- Bookings Display -->
                        <?php if($selected_session): ?>
                            <div class="bookings-section">
                                <h4 class="text-center mb-4">
                                    <?php echo ucfirst($selected_session); ?> Session Bookings for <?php echo date('d M Y', strtotime($selected_date)); ?>
                                </h4>

                                <?php
                                $bookings_query = "SELECT sb.*, r.full_name, r.mobile_no, r.preferred_session
                                                 FROM slot_bookings sb
                                                 JOIN register r ON sb.user_id = r.user_id
                                                 JOIN staffassignedmembers sam ON sb.user_id = sam.member_id
                                                 WHERE sb.booking_date = ?
                                                 AND sb.time_slot = ?
                                                 AND sam.trainer_id = ?
                                                 AND sb.cancelled_at IS NULL
                                                 ORDER BY sb.created_at ASC";

                                $stmt = $conn->prepare($bookings_query);
                                $stmt->bind_param("ssi", $selected_date, $selected_session, $trainer_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if($result->num_rows > 0):
                                    while($booking = $result->fetch_assoc()):
                                ?>
                                    <div class="card mb-3 booking-card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-1 text-center">
                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                </div>
                                                <div class="col-md-4">
                                                    <h5 class="mb-0"><?php echo htmlspecialchars($booking['full_name']); ?></h5>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone"></i> <?php echo htmlspecialchars($booking['mobile_no']); ?>
                                                    </small>
                                                </div>
                                                <div class="col-md-4">
                                                    <span class="badge <?php echo $booking['time_slot'] === $booking['preferred_session'] ? 'bg-success' : 'bg-info'; ?>">
                                                        <?php echo $booking['time_slot'] === $booking['preferred_session'] ? 'Regular Session' : 'Additional Session'; ?>
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Booked on: <?php echo date('d M Y, h:i A', strtotime($booking['created_at'])); ?>
                                                    </small>
                                                </div>
                                                <div class="col-md-3 text-end">
                                                    <?php if($booking['booking_date'] == $today): ?>
                                                        <span class="badge bg-warning">Today's Session</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-primary">Upcoming</span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No bookings found for this session</h5>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-hand-point-up fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Please select a session to view bookings</h5>
                            </div>
                        <?php endif; ?>

                        <!-- Back Button -->
                        <div class="text-center mt-4">
                            <a href="staff.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .booking-card {
        transition: transform 0.2s;
        border-left: 4px solid #0d6efd;
    }
    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .badge {
        padding: 8px 12px;
        font-size: 0.9em;
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const datePicker = document.getElementById('datePicker');
        
        // Disable weekend dates
        datePicker.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const day = selectedDate.getDay();
            
            // 0 is Sunday, 6 is Saturday
            if (day === 0 || day === 6) {
                alert('Weekends (Saturday & Sunday) are not available for booking. Please select a working day.');
                this.value = ''; // Clear the input
            }
        });

        // Add date validation to the form submission
        const dateForm = document.querySelector('form');
        if (dateForm) {
            dateForm.addEventListener('submit', function(e) {
                const dateInput = this.querySelector('input[type="date"]');
                const selectedDate = new Date(dateInput.value);
                const day = selectedDate.getDay();
                
                if (day === 0 || day === 6) {
                    e.preventDefault();
                    alert('Weekends (Saturday & Sunday) are not available for booking. Please select a working day.');
                }
            });
        }
    });
    </script>
</body>
</html> 