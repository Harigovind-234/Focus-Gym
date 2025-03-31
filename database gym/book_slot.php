<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login2.php");
    exit();
}
include "connect.php";

$user_id = $_SESSION['user_id'];
$session = isset($_GET['session']) ? $_GET['session'] : '';
$booking_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : date('Y-m-d');

// Get user's details and preferred session from register table
$user_query = "SELECT r.preferred_session, r.full_name, r.mobile_no 
               FROM register r 
               WHERE r.user_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$preferred_session = $user_data['preferred_session'];

// Check existing booking for the selected date
$check_booking = "SELECT sb.*, r.full_name, r.mobile_no 
                 FROM slot_bookings sb 
                 JOIN register r ON sb.user_id = r.user_id 
                 WHERE sb.user_id = ? 
                 AND sb.booking_date = ? 
                 AND sb.cancelled_at IS NULL";
$stmt = $conn->prepare($check_booking);
$stmt->bind_param("is", $user_id, $booking_date);
$stmt->execute();
$existing_booking = $stmt->get_result()->fetch_assoc();

// Initialize error message
$error_message = "";

// Define time restrictions
$current_time = strtotime('now');
$morning_end = strtotime('11:00:00');
$current_date = date('Y-m-d');
$selected_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : $current_date;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if selected date is a weekend
    $day_of_week = date('N', strtotime($selected_date));
    if ($day_of_week >= 6) { // 6 = Saturday, 7 = Sunday
        $error_message = "Bookings are not available on weekends. Please select a weekday.";
    }
    // Check if trying to book morning session for current day after 11 AM
    elseif ($session === 'morning' && 
        $selected_date === $current_date && 
        $current_time > $morning_end) {
        $error_message = "Morning session booking for today is closed. Please book for future dates.";
    }
    // Check for existing booking
    elseif ($existing_booking) {
        $error_message = "You already have a booking for " . date('d-m-Y', strtotime($booking_date));
    }
    // Check if trying to book preferred session
    elseif ($session === $preferred_session) {
        $error_message = "You cannot book your regular session time again.";
    }
    else {
        // Check slot capacity (assuming 30 per session)
        $capacity_query = "SELECT COUNT(*) as count 
                          FROM slot_bookings 
                          WHERE booking_date = ? 
                          AND time_slot = ? 
                          AND cancelled_at IS NULL";
        $stmt = $conn->prepare($capacity_query);
        $stmt->bind_param("ss", $booking_date, $session);
        $stmt->execute();
        $slot_count = $stmt->get_result()->fetch_assoc()['count'];

        if ($slot_count >= 30) {
            $error_message = "Sorry, this session is fully booked.";
        } else {
            // Proceed with booking
            $insert_query = "INSERT INTO slot_bookings (user_id, time_slot, booking_date) 
                           VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("iss", $user_id, $session, $booking_date);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Booking successful for " . date('d-m-Y', strtotime($booking_date));
                header("Location: index.php#status-header");
                exit();
            } else {
                $error_message = "Error making booking. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo ucfirst($session); ?> Session</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="booking-container">
        <div class="booking-header">
            <div class="session-icon">
                <i class="fas <?php echo $session === 'morning' ? 'fa-sun' : 'fa-moon'; ?>"></i>
            </div>
            <h2>Book <?php echo ucfirst($session); ?> Session</h2>
        </div>

        <div class="session-info">
            <?php if($session === 'morning'): ?>
                <h3>Morning Session</h3>
                <p>6:00 AM - 11:00 AM</p>
            <?php else: ?>
                <h3>Evening Session</h3>
                <p>4:00 PM - 9:00 PM</p>
            <?php endif; ?>
            <p>Your Regular Session: <?php echo ucfirst($preferred_session); ?></p>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="info-message">
            <i class="fas fa-info-circle"></i>
            <?php if($session === 'morning'): ?>
                Morning session booking is only available between 6 AM and 11 AM on weekdays.
            <?php else: ?>
                Evening session booking is only available between 4 PM and 9 PM on weekdays.
            <?php endif; ?>
            <br>
            <small><i class="fas fa-calendar-times"></i> Bookings are not available on weekends.</small>
        </div>

        <form class="booking-form" method="POST">
            <div class="form-group">
                <label for="booking_date">Choose Date:</label>
                <input type="date" 
                       id="booking_date" 
                       name="booking_date" 
                       min="<?php echo date('Y-m-d'); ?>" 
                       value="<?php echo $booking_date; ?>" 
                       required>
            </div>

            <button type="submit" class="booking-button">
                <i class="fas <?php echo $session === 'morning' ? 'fa-sun' : 'fa-moon'; ?>"></i>
                BOOK <?php echo strtoupper($session); ?> SESSION
            </button>
        </form>

        <!-- Current Bookings Section -->
        
        <a href="index.php#booking" class="back-to-dashboard">
            <i class="fas fa-arrow-left"></i> Back To YY
        </a>
    </div>

<style>
body {
    font-family: 'Arial', sans-serif;
    background: #f8f9fa;
    margin: 0;
    padding: 20px;
}

.booking-container {
    max-width: 500px;
    margin: 0 auto;
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.booking-header {
    text-align: center;
    margin-bottom: 30px;
}

.session-icon {
    width: 80px;
    height: 80px;
    background: #ed563b;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.session-icon i {
    font-size: 40px;
    color: white;
}

.session-info {
    text-align: center;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
}

.session-info h3 {
    color: <?php echo $session === 'morning' ? '#ed563b' : '#2c3e50'; ?>;
    margin-bottom: 10px;
}

.session-info p {
    margin: 5px 0;
    color: #666;
}

.session-info p:first-of-type {
    font-size: 1.2em;
    font-weight: 600;
    color: #232d39;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #232d39;
}

input[type="date"] {
    width: 100%;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
}

.booking-button {
    width: 100%;
    padding: 15px;
    background: #ed563b;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
}

.booking-button:hover {
    background: #da442a;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Add these new styles for evening theme */
.booking-container[data-session="evening"] .session-icon {
    background: #2c3e50;
}

.booking-container[data-session="evening"] .booking-button {
    background: #2c3e50;
}

.booking-container[data-session="evening"] .booking-button:hover {
    background: #34495e;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('booking_date');
    const session = '<?php echo $session; ?>';
    const currentTime = new Date();
    const currentHour = currentTime.getHours();
    
    // Disable weekends and set min date
    dateInput.addEventListener('input', function(e) {
        const selected = new Date(this.value);
        const day = selected.getDay();
        
        // If weekend is selected (0 = Sunday, 6 = Saturday)
        if (day === 0 || day === 6) {
            alert('Weekends are not available for booking. Please select a weekday.');
            this.value = '';
        }
    });

    // Set minimum date and handle morning session cutoff
    if (session === 'morning' && currentHour >= 11) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        // Skip to Monday if tomorrow is Saturday or Sunday
        while (tomorrow.getDay() === 0 || tomorrow.getDay() === 6) {
            tomorrow.setDate(tomorrow.getDate() + 1);
        }
        
        const minDate = tomorrow.toISOString().split('T')[0];
        dateInput.min = minDate;
        
        if (dateInput.value === '<?php echo $current_date; ?>') {
            dateInput.value = minDate;
        }
    } else {
        // Skip weekend for current date selection
        const today = new Date();
        if (today.getDay() === 0 || today.getDay() === 6) {
            const nextWeekday = new Date();
            do {
                nextWeekday.setDate(nextWeekday.getDate() + 1);
            } while (nextWeekday.getDay() === 0 || nextWeekday.getDay() === 6);
            
            dateInput.min = nextWeekday.toISOString().split('T')[0];
            dateInput.value = nextWeekday.toISOString().split('T')[0];
        }
    }
});
</script>
</body>
</html>