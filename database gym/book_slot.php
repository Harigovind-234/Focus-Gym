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

// Define current time and date
$current_time = date('H:i:s');
$current_hour = (int)date('H');
$current_date = date('Y-m-d');
$tomorrow_date = date('Y-m-d', strtotime('+1 day'));
$selected_date = isset($_POST['booking_date']) ? $_POST['booking_date'] : $tomorrow_date;

// Check if current time is within booking windows
$can_book_morning = (($current_hour >= 10 && $current_hour < 16) || ($current_hour >= 21 && $current_hour < 22));
$can_book_evening = (($current_hour >= 16 && $current_hour < 17) || ($current_hour >= 21 && $current_hour < 22));

// Check if gym is currently in session (restricted booking times)
$gym_in_session = (($current_hour >= 6 && $current_hour < 10) || ($current_hour >= 16 && $current_hour < 21));

// For testing/debugging purposes - temporarily override restrictions to ensure button works
// Comment out this line in production
$gym_in_session = false;
$can_book_morning = true;
$can_book_evening = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if selected date is a weekend
    $day_of_week = date('N', strtotime($selected_date));
    if ($day_of_week >= 6) { // 6 = Saturday, 7 = Sunday
        $error_message = "Bookings are not available on weekends. Please select a weekday.";
    }
    // Check if trying to book for the same day
    elseif ($selected_date === $current_date) {
        $error_message = "Bookings must be for tomorrow or later. Today's sessions are no longer available for booking.";
    }
    // Check if gym is in session (restricted booking times)
    elseif ($gym_in_session) {
        $error_message = "Booking is restricted while gym sessions are in progress (6 AM - 10 AM & 4 PM - 9 PM).";
    }
    // Check if booking window is open for the requested session
    elseif (($session === 'morning' && !$can_book_morning) || ($session === 'evening' && !$can_book_evening)) {
        if ($session === 'morning') {
            $error_message = "Morning session booking is only available from 10 AM - 3:59 PM and 9 PM - 9:59 PM.";
        } else {
            $error_message = "Evening session booking is only available from 4 PM - 4:59 PM and 9 PM - 9:59 PM.";
        }
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="booking-container" data-session="<?php echo $session; ?>">
        <div class="booking-header">
            <div class="session-icon">
                <i class="fas <?php echo $session === 'morning' ? 'fa-sun' : 'fa-moon'; ?>"></i>
            </div>
            <h2>Book <?php echo ucfirst($session); ?> Session</h2>
            <div class="badge-container">
                <div class="booking-badge">
                    <i class="fas fa-clock"></i> Next Available
                </div>
            </div>
        </div>

        <div class="session-info">
            <?php if($session === 'morning'): ?>
                <h3>Morning Session</h3>
                <p>6:00 AM - 10:00 AM</p>
                <div class="booking-window">
                    <span class="window-label">Booking Window:</span>
                    <div class="window-time <?php echo $can_book_morning ? 'open' : 'closed'; ?>">
                        <i class="fas <?php echo $can_book_morning ? 'fa-unlock' : 'fa-lock'; ?>"></i>
                        10:00 AM - 3:59 PM & 9:00 PM - 9:59 PM
                    </div>
                </div>
            <?php else: ?>
                <h3>Evening Session</h3>
                <p>4:00 PM - 9:00 PM</p>
                <div class="booking-window">
                    <span class="window-label">Booking Window:</span>
                    <div class="window-time <?php echo $can_book_evening ? 'open' : 'closed'; ?>">
                        <i class="fas <?php echo $can_book_evening ? 'fa-unlock' : 'fa-lock'; ?>"></i>
                        4:00 PM - 4:59 PM & 9:00 PM - 9:59 PM
                    </div>
                </div>
            <?php endif; ?>
            <p class="user-session">Your Regular Session: <span><?php echo ucfirst($preferred_session); ?></span></p>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="booking-status-container">
            <div class="status-item <?php echo $gym_in_session ? 'active' : 'inactive'; ?>">
                <div class="status-icon">
                    <i class="fas <?php echo $gym_in_session ? 'fa-running' : 'fa-pause'; ?>"></i>
                </div>
                <div class="status-text">
                    <?php if($gym_in_session): ?>
                        <span>Gym Session in Progress</span>
                        <small>Booking is restricted during active hours</small>
                    <?php else: ?>
                        <span>Gym Not in Session</span>
                        <small>Booking is available during specified windows</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="info-message">
            <i class="fas fa-info-circle"></i>
            <div>
                <p>Bookings are only for the next available session (tomorrow).</p>
                <p>You cannot book during active gym hours (6 AM - 10 AM & 4 PM - 9 PM).</p>
                <small><i class="fas fa-calendar-times"></i> Bookings are not available on weekends.</small>
            </div>
        </div>

        <form class="booking-form" method="POST" id="bookingForm">
            <div class="form-group">
                <label for="booking_date">Choose Date:</label>
                <input type="date" 
                       id="booking_date" 
                       name="booking_date" 
                       min="<?php echo $tomorrow_date; ?>" 
                       value="<?php echo $tomorrow_date; ?>" 
                       required>
                <div class="date-hint">Select a weekday date</div>
            </div>

            <button type="submit" class="booking-button" id="bookingButton">
                <i class="fas <?php echo $session === 'morning' ? 'fa-sun' : 'fa-moon'; ?>"></i>
                BOOK <?php echo strtoupper($session); ?> SESSION
            </button>
        </form>
        
        <a href="index.php#booking" class="back-to-dashboard">
            <i class="fas fa-arrow-left"></i> Back To Dashboard
        </a>
    </div>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(145deg, #f8f9fa, #e9ecef);
    margin: 0;
    padding: 40px 20px;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.booking-container {
    max-width: 550px;
    width: 100%;
    margin: 0 auto;
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.booking-container:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 8px;
    background: linear-gradient(90deg, #ed563b, #f75c46);
}

.booking-container[data-session="evening"]:before {
    background: linear-gradient(90deg, #2c3e50, #4a6b8a);
}

.booking-header {
    text-align: center;
    margin-bottom: 30px;
    position: relative;
}

.badge-container {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}

.booking-badge {
    background: #ed563b;
    color: white;
    font-size: 14px;
    padding: 5px 15px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    box-shadow: 0 3px 10px rgba(237, 86, 59, 0.2);
}

.booking-container[data-session="evening"] .booking-badge {
    background: #2c3e50;
    box-shadow: 0 3px 10px rgba(44, 62, 80, 0.2);
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
    box-shadow: 0 5px 15px rgba(237, 86, 59, 0.3);
    transition: all 0.3s ease;
}

.booking-container[data-session="evening"] .session-icon {
    background: #2c3e50;
    box-shadow: 0 5px 15px rgba(44, 62, 80, 0.3);
}

.session-icon i {
    font-size: 40px;
    color: white;
}

.booking-header h2 {
    font-weight: 600;
    color: #232d39;
    margin: 0;
    font-size: 28px;
}

.session-info {
    text-align: center;
    background: #f8f9fa;
    padding: 25px;
    border-radius: 12px;
    margin: 25px 0;
    position: relative;
    box-shadow: 0 5px 15px rgba(0,0,0,0.03);
}

.session-info h3 {
    color: #ed563b;
    margin: 0 0 10px 0;
    font-size: 22px;
    font-weight: 600;
}

.booking-container[data-session="evening"] .session-info h3 {
    color: #2c3e50;
}

.session-info p {
    margin: 5px 0 15px;
    color: #666;
    font-size: 16px;
}

.user-session {
    color: #232d39 !important;
    font-weight: 500 !important;
    margin-top: 15px !important;
    padding-top: 15px;
    border-top: 1px solid rgba(0,0,0,0.05);
}

.user-session span {
    color: #ed563b;
    font-weight: 600;
}

.booking-container[data-session="evening"] .user-session span {
    color: #2c3e50;
}

.booking-window {
    margin: 15px 0;
    padding: 10px 15px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
}

.window-label {
    font-size: 14px;
    color: #666;
    display: block;
    margin-bottom: 8px;
}

.window-time {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 15px;
    transition: all 0.3s ease;
}

.window-time.open {
    color: #28a745;
    background: rgba(40, 167, 69, 0.1);
}

.window-time.closed {
    color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.booking-status-container {
    margin: 25px 0;
}

.status-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 10px;
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.status-item.active {
    background: rgba(220, 53, 69, 0.1);
    border-left: 4px solid #dc3545;
}

.status-item.inactive {
    background: rgba(40, 167, 69, 0.1);
    border-left: 4px solid #28a745;
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    margin-right: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.status-item.active .status-icon i {
    color: #dc3545;
}

.status-item.inactive .status-icon i {
    color: #28a745;
}

.status-text span {
    display: block;
    color: #232d39;
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 5px;
}

.status-text small {
    color: #6c757d;
    font-size: 14px;
}

.form-group {
    margin-bottom: 25px;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    color: #232d39;
    font-weight: 500;
    font-size: 16px;
}

.date-hint {
    font-size: 13px;
    color: #6c757d;
    margin-top: 8px;
}

input[type="date"] {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s ease;
    color: #495057;
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
}

input[type="date"]:focus {
    border-color: #ed563b;
    box-shadow: 0 0 0 3px rgba(237, 86, 59, 0.1);
    outline: none;
}

.booking-container[data-session="evening"] input[type="date"]:focus {
    border-color: #2c3e50;
    box-shadow: 0 0 0 3px rgba(44, 62, 80, 0.1);
}

.booking-button {
    width: 100%;
    padding: 16px;
    background: #ed563b;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(237, 86, 59, 0.2);
}

.booking-button:hover {
    background: #da442a;
    transform: translateY(-2px);
}

.booking-button:disabled {
    background: #868e96;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.booking-container[data-session="evening"] .booking-button {
    background: #2c3e50;
    box-shadow: 0 4px 15px rgba(44, 62, 80, 0.2);
}

.booking-container[data-session="evening"] .booking-button:hover {
    background: #34495e;
}

.booking-container[data-session="evening"] .booking-button:disabled {
    background: #868e96;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 25px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 15px;
    line-height: 1.5;
    box-shadow: 0 3px 10px rgba(0,0,0,0.03);
}

.error-message i {
    color: #dc3545;
    font-size: 18px;
    margin-top: 3px;
}

.info-message {
    display: flex;
    background: #e2f3ff;
    color: #0c5460;
    padding: 15px;
    border-radius: 10px;
    margin: 25px 0;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    line-height: 1.6;
    box-shadow: 0 3px 10px rgba(0,0,0,0.03);
}

.info-message i {
    color: #17a2b8;
    font-size: 18px;
    margin-top: 3px;
}

.info-message p {
    margin: 0 0 8px 0;
}

.info-message small {
    display: block;
    margin-top: 8px;
    color: #0c5460;
    opacity: 0.9;
}

.back-to-dashboard {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #6c757d;
    text-decoration: none;
    font-weight: 500;
    margin-top: 20px;
    padding: 10px 0;
    transition: all 0.3s ease;
}

.back-to-dashboard:hover {
    color: #ed563b;
}

.booking-container[data-session="evening"] .back-to-dashboard:hover {
    color: #2c3e50;
}

@media (max-width: 576px) {
    body {
        padding: 20px 15px;
    }
    
    .booking-container {
        padding: 25px 20px;
    }
    
    .session-icon {
        width: 70px;
        height: 70px;
    }
    
    .session-icon i {
        font-size: 30px;
    }
    
    .booking-header h2 {
        font-size: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('booking_date');
    const session = '<?php echo $session; ?>';
    const bookingForm = document.getElementById('bookingForm');
    const bookingButton = document.getElementById('bookingButton');
    
    // Current time conditions
    const canBookMorning = <?php echo $can_book_morning ? 'true' : 'false'; ?>;
    const canBookEvening = <?php echo $can_book_evening ? 'true' : 'false'; ?>;
    const gymInSession = <?php echo $gym_in_session ? 'true' : 'false'; ?>;
    
    // Set button state based on conditions
    function updateButtonState() {
        const disableButton = 
            (session === 'morning' && !canBookMorning) || 
            (session === 'evening' && !canBookEvening) || 
            gymInSession;
            
        bookingButton.disabled = disableButton;
        
        if (disableButton) {
            bookingButton.classList.add('disabled');
            if (gymInSession) {
                bookingButton.setAttribute('title', 'Booking is restricted during active gym hours');
            } else {
                bookingButton.setAttribute('title', 'Booking is not available during this time window');
            }
        } else {
            bookingButton.classList.remove('disabled');
            bookingButton.setAttribute('title', 'Book your session now');
        }
    }
    
    // Initialize button state
    updateButtonState();
    
    // Disable weekends
    dateInput.addEventListener('input', function(e) {
        const selected = new Date(this.value);
        const day = selected.getDay();
        
        // If weekend is selected (0 = Sunday, 6 = Saturday)
        if (day === 0 || day === 6) {
            alert('Weekends are not available for booking. Please select a weekday.');
            // Set to next weekday
            const nextWeekday = new Date();
            nextWeekday.setDate(nextWeekday.getDate() + 1); // Start with tomorrow
            
            // Find next weekday
            while (nextWeekday.getDay() === 0 || nextWeekday.getDay() === 6) {
                nextWeekday.setDate(nextWeekday.getDate() + 1);
            }
            
            this.value = nextWeekday.toISOString().split('T')[0];
        }
    });
    
    // Handle form submission
    bookingForm.addEventListener('submit', function(e) {
        if (bookingButton.disabled) {
            e.preventDefault();
            
            let errorMessage;
            if (gymInSession) {
                errorMessage = 'Booking is restricted during active gym hours (6 AM - 10 AM & 4 PM - 9 PM).';
            } else if (session === 'morning' && !canBookMorning) {
                errorMessage = 'Morning session booking is only available from 10 AM - 3:59 PM and 9 PM - 9:59 PM.';
            } else if (session === 'evening' && !canBookEvening) {
                errorMessage = 'Evening session booking is only available from 4 PM - 4:59 PM and 9 PM - 9:59 PM.';
            } else {
                errorMessage = 'Booking is not available at this time.';
            }
            
            alert(errorMessage);
            return false;
        }
        
        // Add loading state to button
        bookingButton.disabled = true;
        bookingButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> PROCESSING...';
    });
});
</script>
</body>
</html>