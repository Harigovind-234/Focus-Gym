<?php 
session_start();
include "connect.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <title>Training Studio - Free CSS Template</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">
    <link rel="stylesheet" href="membership.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="assets/css/edit-profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
.schedule-section {
            background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
            padding: 80px 0;
            color: #fff;
        }

        .gradient-text {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .subtitle {
            color: #888;
            font-size: 1.2rem;
            margin-bottom: 40px;
        }

        .timetable-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .day-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
        }

        .day-header {
            font-size: 1.2rem;
            font-weight: 600;
            color: #ff6b6b;
        }

        .day-subtitle {
            font-size: 0.8rem;
            color: #888;
            margin: 5px 0;
        }

        .day-icon {
            font-size: 1.2rem;
            color: #4ecdc4;
            margin-top: 5px;
        }

        .workout-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            transition: transform 0.3s ease;
        }

        .workout-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .workout-icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #4ecdc4;
        }

        .workout-info h4 {
            color: #fff;
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .intensity {
            display: block;
            font-size: 0.8rem;
            color: #ff6b6b;
            margin-bottom: 3px;
        }

        .trainer {
            display: block;
            font-size: 0.8rem;
            color: #888;
        }

        .time-container {
            text-align: center;
            padding: 10px;
        }

        .time-main {
            display: block;
            font-size: 1rem;
            color: #4ecdc4;
            font-weight: 600;
        }

        .time-sub {
            display: block;
            font-size: 0.8rem;
            color: #888;
            margin-top: 5px;
        }

        /* Workout type specific colors */
        .cardio .workout-icon { color: #ff6b6b; }
        .strength .workout-icon { color: #4ecdc4; }
        .hiit .workout-icon { color: #ffd93d; }
        .weights .workout-icon { color: #6c5ce7; }
        .yoga .workout-icon { color: #a8e6cf; }
 
    <!-- Profile Section with Updated Design -->
    /* Updated Profile Avatar Styles */
    .profile-avatar {
        position: relative;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #ed563b;
        box-shadow: 0 0 20px rgba(237,86,59,0.3);
        background: linear-gradient(45deg, #ed563b, #ff7d6b);
        cursor: pointer;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-initials {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: calc(90px * 0.4);
        font-weight: bold;
        color: white;
        text-transform: uppercase;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .avatar-overlay i {
        color: white;
        font-size: 24px;
    }

    .profile-avatar:hover .avatar-overlay {
        opacity: 1;
    }

    /* Update the modal form styles */
    .modal-content {
        max-height: 80vh;
        overflow-y: auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    <style>
    .product-card {
        background: linear-gradient(145deg,rgb(91, 88, 88),rgb(78, 73, 73));
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image {
        width: 100%;
        height: 250px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        padding: 20px;
    }

    .product-info {
        padding: 20px;
    }

    .product-info h4 {
        color: #232d39;
        margin-bottom: 10px;
    }

    .category {
        color: #ed563b;
        font-size: 14px;
        display: block;
        margin-bottom: 10px;
    }

    .price {
        font-size: 24px;
        color: #232d39;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .description {
        color: #7a7a7a;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .modal-content {
        border-radius: 15px;
    }

    .modal-header {
        background-color: #ed563b;
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .pickup-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }

    .total-price {
        font-size: 18px;
        padding: 10px 0;
        border-top: 1px solid #dee2e6;
    }
    /* Modal styles */
.modal {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-header .close {
    color: white;
}

.form-group {
    margin-bottom: 1rem;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
}

.btn-primary {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #4ecdc4, #ff6b6b);
}

/* Make modal visible */
.modal.show {
    display: block !important;
    opacity: 1 !important;
}

.modal-backdrop {
    opacity: 0.5;
}

/* Ensure modal is above other elements */
.modal {
    z-index: 1050 !important;
}

.modal-backdrop {

    z-index: 1040 !important;
}


    </style>


    </head>
    <body>
        <!-- ***** Preloader Start ***** -->
        <div id="js-preloader" class="js-preloader">
            <div class="preloader-inner">
                <span class="dot"></span>
                <div class="dots"></div>
            </div>
        </div>
        <!-- ***** Preloader End ***** -->
        <!-- ***** Header Area Start ***** -->
        <header class="header-area header-sticky">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="main-nav"><!-- ***** Logo Start ***** -->
                            <a href="index.html" class="logo" ><img src=""></a><!-- ***** Logo End ***** -->
                                <!-- ***** Menu Start ***** -->
                                <ul class="nav">
                                   
                                    <li class="scroll-to-section"><a href="#features">profile</a></li>
                                    <li class="scroll-to-section"><a href="#our-classes">Classes</a></li>
                                    <li class="scroll-to-section"><a href="#schedule">Schedules</a></li>
                                    <li class="scroll-to-section"><a href="#products">Products</a></li> 
                                    <li class="main-button"><a href="logout.php">Logout</a></li>
                                </ul>        
                            <a class='menu-trigger'><span>Menu</span></a>
                            <!-- ***** Menu End ***** -->
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!--Profile-->
        <!---->
        
        <section class="section" id="features">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="section-heading">
                            <h2>Welcome to <em>Your Profile</em></h2>
                            <img src="assets/images/line-dec.png" alt="">
                            <p>Track your fitness journey and manage your personal information</p>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-avatar" onclick="document.getElementById('profile_pic').click()">
                            <?php if (!empty($_SESSION['profile_pic'])): ?>
                                <img src="uploads/profile/<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile Picture" class="avatar-image">
                            <?php else: ?>
                                <i class="fa fa-camera upload-icon"></i>
                            <?php endif; ?>
                            <div class="avatar-overlay">
                                <i class="fa fa-camera"></i>
                            </div>
                            <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="display: none;">
                        </div>
                        <div class="profile-info">
                            <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                            <p class="member-status">Premium Member</p>
                            <p class="member-since">Member since <?php echo date('F j, Y', strtotime($_SESSION['date'])); ?></p>
                        </div>
                        <div class="profile-actions">
                            <button type="button" class="btn btn-primary edit-button" data-toggle="modal" data-target="#editProfileModal">
                                <i class="fa fa-edit"></i> Edit Profile
                            </button>
                        </div>
                    </div>

                    <!-- Profile Cards -->
                    <div class="profile-content">
                        <div class="profile-card">
                            <h4>Personal Information</h4>
                            <p><strong>Email:</strong> <span id="profile-email"><?php echo htmlspecialchars($_SESSION['email']); ?></span></p>
                            <p><strong>Phone:</strong> <span id="profile-phone"><?php echo htmlspecialchars($_SESSION['mobile']); ?></span></p>
                            <p><strong>Address:</strong> <span id="profile-address"><?php echo htmlspecialchars($_SESSION['address']); ?></span></p>
                        </div>

                        <div class="profile-card">
                            <h4>Membership Details</h4>
                            <p><strong>Plan:</strong> Premium</p>
                            <p><strong>Status:</strong> Active</p>
                            <p><strong>Next Payment:</strong> <?php echo date('F j, Y', strtotime('+1 month')); ?></p>
                        </div>

                        <div class="profile-card">
                            <h4>Fitness Stats</h4>
                            <p><strong>Workouts:</strong> 12 this month</p>
                            <p><strong>Classes:</strong> 5 attended</p>
                            <p><strong>Progress:</strong> On track</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
     <!-- Updated Edit Profile Modal HTML -->
     <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="alertPlaceholder"></div>
                    <form id="profileForm">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="mobile_no">Mobile Number</label>
                            <input type="tel" class="form-control" id="mobile_no" name="mobile_no" 
                                   value="<?php echo htmlspecialchars($_SESSION['mobile'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($_SESSION['address'] ?? ''); ?></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"  data-dismiss="modal" id="saveProfileBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

   
    <!--classes-->
    <!---->
    <section class="section" id="our-classes">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Our <em>Classes</em></h2>
                        <img src="assets/images/line-dec.png" alt="">
                        <p>Nunc urna sem, laoreet ut metus id, aliquet consequat magna. Sed viverra ipsum dolor, ultricies fermentum massa consequat eu.</p>
                    </div>
                </div>
            </div>
            <div class="row" id="tabs">
              <div class="col-lg-4">
                <ul>
                  <li><a href='#tabs-1'><img src="assets/images/tabs-first-icon.png" alt="">First Training Class</a></li>
                  <li><a href='#tabs-2'><img src="assets/images/tabs-first-icon.png" alt="">Second Training Class</a></a></li>
                  <li><a href='#tabs-3'><img src="assets/images/tabs-first-icon.png" alt="">Third Training Class</a></a></li>
                  <li><a href='#tabs-4'><img src="assets/images/tabs-first-icon.png" alt="">Fourth Training Class</a></a></li>
                  <div class="main-rounded-button"><a href="#">View All Schedules</a></div>
                </ul>
              </div>
              <div class="col-lg-8">
                <section class='tabs-content'>
                  <article id='tabs-1'>
                    <img src="assets/images/training-image-01.jpg" alt="First Class">
                    <h4>First Training Class</h4>
                    <p>Phasellus convallis mauris sed elementum vulputate. Donec posuere leo sed dui eleifend hendrerit. Sed suscipit suscipit erat, sed vehicula ligula. Aliquam ut sem fermentum sem tincidunt lacinia gravida aliquam nunc. Morbi quis erat imperdiet, molestie nunc ut, accumsan diam.</p>
                    <div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>
                  </article>
                  <article id='tabs-2'>
                    <img src="assets/images/training-image-02.jpg" alt="Second Training">
                    <h4>Second Training Class</h4>
                    <p>Integer dapibus, est vel dapibus mattis, sem mauris luctus leo, ac pulvinar quam tortor a velit. Praesent ultrices erat ante, in ultricies augue ultricies faucibus. Nam tellus nibh, ullamcorper at mattis non, rhoncus sed massa. Cras quis pulvinar eros. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                    <div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>
                  </article>
                  <article id='tabs-3'>
                    <img src="assets/images/training-image-03.jpg" alt="Third Class">
                    <h4>Third Training Class</h4>
                    <p>Fusce laoreet malesuada rhoncus. Donec ultricies diam tortor, id auctor neque posuere sit amet. Aliquam pharetra, augue vel cursus porta, nisi tortor vulputate sapien, id scelerisque felis magna id felis. Proin neque metus, pellentesque pharetra semper vel, accumsan a neque.</p>
                    <div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>
                  </article>
                  <article id='tabs-4'>
                    <img src="assets/images/training-image-04.jpg" alt="Fourth Training">
                    <h4>Fourth Training Class</h4>
                    <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean ultrices elementum odio ac tempus. Etiam eleifend orci lectus, eget venenatis ipsum commodo et.</p>
                    <div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>
                  </article>
                </section>
              </div>
            </div>
        </div>
    </section>
    <!-- ***** Our Classes End ***** -->
    
    <!-- <section class="section" id="schedule">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading dark-bg">
                        <h2>Classes <em>Schedule</em></h2>
                        <img src="assets/images/line-dec.png" alt="">
                        <p>Nunc urna sem, laoreet ut metus id, aliquet consequat magna. Sed viverra ipsum dolor, ultricies fermentum massa consequat eu.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="filters">
                        <ul class="schedule-filter">
                            <li class="active" data-tsfilter="monday">Monday</li>
                            <li data-tsfilter="tuesday">Tuesday</li>
                            <li data-tsfilter="wednesday">Wednesday</li>
                            <li data-tsfilter="thursday">Thursday</li>
                            <li data-tsfilter="friday">Friday</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-10 offset-lg-1">
                    <div class="schedule-table filtering">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="day-time">Fitness Class</td>
                                    <td class="monday ts-item show" data-tsmeta="monday">10:00AM - 11:30AM</td>
                                    <td class="tuesday ts-item" data-tsmeta="tuesday">2:00PM - 3:30PM</td>
                                    <td>William G. Stewart</td>
                                </tr>
                                <tr>
                                    <td class="day-time">Muscle Training</td>
                                    <td class="friday ts-item" data-tsmeta="friday">10:00AM - 11:30AM</td>
                                    <td class="thursday friday ts-item" data-tsmeta="thursday" data-tsmeta="friday">2:00PM - 3:30PM</td>
                                    <td>Paul D. Newman</td>
                                </tr>
                                <tr>
                                    <td class="day-time">Body Building</td>
                                    <td class="tuesday ts-item" data-tsmeta="tuesday">10:00AM - 11:30AM</td>
                                    <td class="monday ts-item show" data-tsmeta="monday">2:00PM - 3:30PM</td>
                                    <td>Boyd C. Harris</td>
                                </tr>
                                <tr>
                                    <td class="day-time">Yoga Training Class</td>
                                    <td class="wednesday ts-item" data-tsmeta="wednesday">10:00AM - 11:30AM</td>
                                    <td class="friday ts-item" data-tsmeta="friday">2:00PM - 3:30PM</td>
                                    <td>Hector T. Daigle</td>
                                </tr>
                                <tr>
                                    <td class="day-time">Advanced Training</td>
                                    <td class="thursday ts-item" data-tsmeta="thursday">10:00AM - 11:30AM</td>
                                    <td class="wednesday ts-item" data-tsmeta="wednesday">2:00PM - 3:30PM</td>
                                    <td>Bret D. Bowers</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- ***** Testimonials Starts ***** -->
    <!-- <section class="section" id="trainers">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Expert <em>Trainers</em></h2>
                        <img src="assets/images/line-dec.png" alt="">
                        <p>Nunc urna sem, laoreet ut metus id, aliquet consequat magna. Sed viverra ipsum dolor, ultricies fermentum massa consequat eu.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="assets/images/first-trainer.jpg" alt="">
                        </div>
                        <div class="down-content">
                            <span>Strength Trainer</span>
                            <h4>Bret D. Bowers</h4>
                            <p>Bitters cliche tattooed 8-bit distillery mustache. Keytar succulents gluten-free vegan church-key pour-over seitan flannel.</p>
                            <ul class="social-icons">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-behance"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="assets/images/second-trainer.jpg" alt="">
                        </div>
                        <div class="down-content">
                            <span>Muscle Trainer</span>
                            <h4>Hector T. Daigl</h4>
                            <p>Bitters cliche tattooed 8-bit distillery mustache. Keytar succulents gluten-free vegan church-key pour-over seitan flannel.</p>
                            <ul class="social-icons">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-behance"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="trainer-item">
                        <div class="image-thumb">
                            <img src="assets/images/third-trainer.jpg" alt="">
                        </div>
                        <div class="down-content">
                            <span>Power Trainer</span>
                            <h4>Paul D. Newman</h4>
                            <p>Bitters cliche tattooed 8-bit distillery mustache. Keytar succulents gluten-free vegan church-key pour-over seitan flannel.</p>
                            <ul class="social-icons">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-behance"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!--schedule-->
    <section id="schedule" class="section schedule-section">
        <div class="container">
            <div class="section-title text-center mb-5">
                <h2 class="gradient-text">Weekly <em>Schedule</em></h2>
                <p class="subtitle">Your Path to Fitness Excellence</p>
            </div>
            
            <div class="schedule-wrapper">
                <div class="timetable-card">
                    <table class="table table-hover custom-table" id="scheduleTable">
                        <thead>
                            <tr>
                                <th class="time-column">
                                    <div class="time-header">
                                        <!-- <i class="fas fa-clock"></i> -->
                                        <span>Time Slot</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="day-container">
                                        <span class="day-header">Monday</span>
                                        <span class="day-subtitle">Start Strong</span>
                                        <!-- <i class="fas fa-sun day-icon"></i> -->
                                    </div>
                                </th>
                                <th>
                                    <div class="day-container">
                                        <span class="day-header">Tuesday</span>
                                        <span class="day-subtitle">Power Up</span>
                                        <!-- <i class="fas fa-bolt day-icon"></i> -->
                                    </div>
                                </th>
                                <th>
                                    <div class="day-container">
                                        <span class="day-header">Wednesday</span>
                                        <span class="day-subtitle">Mid-Week Push</span>
                                        <!-- <i class="fas fa-fire day-icon"></i> -->
                                    </div>
                                </th>
                                <th>
                                    <div class="day-container">
                                        <span class="day-header">Thursday</span>
                                        <span class="day-subtitle">Stay Focused</span>
                                        <!-- <i class="fas fa-dumbbell day-icon"></i> -->
                                    </div>
                                </th>
                                <th>
                                    <div class="day-container">
                                        <span class="day-header">Friday</span>
                                        <span class="day-subtitle">Finish Strong</span>
                                        <!-- <i class="fas fa-star day-icon"></i> -->
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="time-slot">
                                    <div class="time-container">
                                        <span class="time-main">6:00 AM - 8:00 AM</span>
                                        <span class="time-sub">Early Bird Special</span>
                                    </div>
                                </td>
                                <td class="workout-cell">
                                    <div class="workout-card cardio">
                                        <div class="workout-icon">
                                            <!-- <i class="fas fa-running"></i> -->
                                        </div>
                                        <div class="workout-info">
                                            <h4>Cardio Blast</h4>
                                            <span class="intensity">Medium Intensity</span>
                                            <!-- <span class="trainer">with John D.</span> -->
                                        </div>
                                    </div>
                                </td>
                                <td class="workout-cell">
                                    <div class="workout-card strength">
                                        <div class="workout-icon">
                                            <!-- <i class="fas fa-dumbbell"></i> -->
                                        </div>
                                        <div class="workout-info">
                                            <h4>Power Lifting</h4>
                                            <span class="intensity">High Intensity</span>
                                            <!-- <span class="trainer">with Mike R.</span> -->
                                        </div>
                                    </div>
                                </td>
                                <td class="workout-cell">
                                    <div class="workout-card hiit">
                                        <div class="workout-icon">
                                            <!-- <i class="fas fa-bolt"></i> -->
                                        </div>
                                        <div class="workout-info">
                                            <h4>HIIT Circuit</h4>
                                            <span class="intensity">High Intensity</span>
                                            <!-- <span class="trainer">with Sarah P.</span> -->
                                        </div>
                                    </div>
                                </td>
                                <td class="workout-cell">
                                    <div class="workout-card weights">
                                        <div class="workout-icon">
                                            <!-- <i class="fas fa-weight-hanging"></i> -->
                                        </div>
                                        <div class="workout-info">
                                            <h4>Strength & Core</h4>
                                            <span class="intensity">High Intensity</span>
                                            <!-- <span class="trainer">with Alex M.</span> -->
                                        </div>
                                    </div>
                                </td>
                                <td class="workout-cell">
                                    <div class="workout-card yoga">
                                        <div class="workout-icon">
                                            <!-- <i class="fas fa-peace"></i> -->
                                        </div>
                                        <div class="workout-info">
                                            <h4>Power Yoga</h4>
                                            <span class="intensity">Medium Intensity</span>
                                            <!-- <span class="trainer">with Lisa K.</span> -->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

         </section>
    <!--membership-->
    <section>
        <div class="section-heading">
            <h2>ADD <em>YOUR</em> <em>Membership</em></h2>
            <img src="assets/images/line-dec.png" alt="">
            <p>Nunc urna sem, laoreet ut metus id, aliquet consequat magna. Sed viverra ipsum dolor, ultricies fermentum massa consequat eu.</p>
        </div>
        <div class="membership-container">
            <div class="membership-card">
                <div class="membership-image basic">
                    <h3>Basic</h3>
                </div>
                <h4>Basic Membership</h4>
                <p class="price">$30/month</p>
                <p class="duration">1-month access</p>
                <p>
                  Access to gym facilities, group classes, and basic support.
                </p>
                <div class="social-links" style="margin-top: 10px;">
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-facebook"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-twitter"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-linkedin"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-behance"></i></a>
                </div>
            </div>
        
            <div class="membership-card">
                <div class="membership-image premium">
                    <h3>Premium</h3>
                </div>
                <h4>Premium Membership</h4>
                <p class="price">$50/month</p>
                <p class="duration">3-month access</p>
                <p>
                  All Basic Membership benefits + personal training sessions and spa access.
                </p>
                <div class="social-links" style="margin-top: 10px;">
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-facebook"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-twitter"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-linkedin"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-behance"></i></a>
                </div>
            </div>
        
            <div class="membership-card">
                <div class="membership-image vip">
                    <h3>VIP</h3>
                </div>
                <h4>VIP Membership</h4>
                <p class="price">$80/month</p>
                <p class="duration">6-month access</p>
                <p>
                  All Premium Membership benefits + VIP lounge access and priority booking.
                </p>
                <div class="social-links" style="margin-top: 10px;">
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-facebook"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-twitter"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-linkedin"></i></a>
                  <a href="#" style="margin: 0 5px;"><i class="fab fa-behance"></i></a>
                </div>
            </div>
        </div>
      </section>
      

   

    <!-- Products -->
    
    <section class="section" id="products">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Our <em>Products</em></h2>
                        <img src="assets/images/line-dec.png" alt="">
                        <p>Browse through our collection of fitness equipment and supplements</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php
                $products_query = "SELECT p.*, c.category_name 
                                 FROM products p 
                                 LEFT JOIN categories c ON p.category_id = c.category_id 
                                 ORDER BY p.product_id DESC";
                $products_result = mysqli_query($conn, $products_query);

                if (mysqli_num_rows($products_result) > 0):
                    while ($product = mysqli_fetch_assoc($products_result)):
                ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($product['image_path']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                            </div>
                            <div class="product-info">
                                <h4><?php echo htmlspecialchars($product['product_name']); ?></h4>
                                <span class="category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                                <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                                <button class="main-button" onclick="orderProduct(<?php echo $product['product_id']; ?>, '<?php echo htmlspecialchars($product['product_name']); ?>', <?php echo $product['price']; ?>)">
                                    Order Now
                                </button>
                            </div>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="col-12 text-center">
                        <p>No products available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Order Modal -->
        <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Order</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="orderForm">
                            <input type="hidden" id="product_id" name="product_id">
                            <div class="form-group">
                                <label>Product:</label>
                                <input type="text" id="product_name" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Price:</label>
                                <input type="text" id="product_price" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Quantity:</label>
                                <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" required>
                            </div>
                            <div class="total-price mt-3">
                                <strong>Total: </strong><span id="total_price"></span>
                            </div>
                            <div class="pickup-info mt-3">
                                <p class="text-info">
                                    <i class="fa fa-info-circle"></i> 
                                    Your order can be picked up at the gym reception during operating hours.
                                </p>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitOrder()">Confirm Order</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Order Successful!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fa fa-check-circle text-success" style="font-size: 48px;"></i>
                            <p class="mt-3">Your order has been placed successfully!</p>
                            <p>Please collect your order from the gym reception.</p>
                            <p class="order-number mt-3"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
     <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2020 Training Studio
                    
                    - Designed by <a rel="nofollow" href="https://templatemo.com" class="tm-text-link" target="_parent">TemplateMo</a><br>

                Distributed by <a rel="nofollow" href="https://themewagon.com" class="tm-text-link" target="_blank">ThemeWagon</a>
                
                </p>
                    
                    <!-- You shall support us a little via PayPal to info@templatemo.com -->
                    
                </div>
            </div>
        </div>
    </footer> 

    
<!-- End of page -->
    
  </body>
</html>

<!-- jQuery -->
<script src="assets/js/jquery-2.1.0.min.js"></script>

<!-- Bootstrap -->
<script src="assets/js/popper.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<!-- Plugins -->
<script src="assets/js/scrollreveal.min.js"></script>
<script src="assets/js/waypoints.min.js"></script>
<script src="assets/js/jquery.counterup.min.js"></script>
<script src="assets/js/imgfix.min.js"></script> 
<script src="assets/js/mixitup.js"></script> 
<script src="assets/js/accordions.js"></script>

<!-- Global Init -->
<script src="assets/js/custom.js"></script>
<script>
    // Form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('update_profile.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin' // This ensures cookies are sent with the request
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating profile. Please try again.');
    });
});

    // Modal control functions
    function openEditModal() {
        document.getElementById('editProfileModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('editProfileModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editProfileModal');
        if (event.target === modal) {
            closeModal();
        }
    }
    </script>
<script>
    let currentPrice = 0;

    function orderProduct(productId, productName, price) {
        currentPrice = price;
        document.getElementById('product_id').value = productId;
        document.getElementById('product_name').value = productName;
        document.getElementById('product_price').value = '₹' + price.toFixed(2);
        updateTotal();
        $('#orderModal').modal('show');
    }

    function updateTotal() {
        const quantity = document.getElementById('quantity').value;
        const total = currentPrice * quantity;
        document.getElementById('total_price').textContent = '₹' + total.toFixed(2);
    }

    // Update total when quantity changes
    document.getElementById('quantity').addEventListener('change', updateTotal);
    document.getElementById('quantity').addEventListener('input', updateTotal);

    function submitOrder() {
        const formData = new FormData(document.getElementById('orderForm'));
        
        fetch('process_order.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#orderModal').modal('hide');
                $('.order-number').text('Order #: ' + data.order_id);
                $('#successModal').modal('show');
                document.getElementById('orderForm').reset();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing your order.');
        });
    }

    // Auto-close success modal after 3 seconds
    $('#successModal').on('shown.bs.modal', function () {
        setTimeout(() => {
            $('#successModal').modal('hide');
        }, 3000);
    });
    </script>
 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

<script>

function showAlert(message, type = 'success') {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;

    const alertPlaceholder = $('#alertPlaceholder');
    alertPlaceholder.html(alertHtml);

    setTimeout(function() {
        alertPlaceholder.find('.alert').alert('close');
    }, 3000);
}
// Test if jQuery is loaded
$(document).ready(function() {
    console.log('jQuery loaded:', typeof $);
    console.log('Bootstrap modal:', typeof $.fn.modal);

    // Manual modal trigger
    $('.edit-button').on('click', function() {
        console.log('Edit button clicked');
        $('#editProfileModal').modal('show');
    });

    // Modal event listeners
    $('#editProfileModal').on('show.bs.modal', function () {
        console.log('Modal is opening');
    }).on('shown.bs.modal', function () {
        console.log('Modal is fully opened');
    }).on('hide.bs.modal', function () {
        console.log('Modal is closing');
        
    });
});

function updateProfile() {
    const formData = new FormData(document.getElementById('profileForm'));

    fetch('update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Get raw response
    .then(text => {
        console.log('Raw response:', text); // Log raw response
        return JSON.parse(text); // Try parsing JSON
    })
    .then(data => {
        console.log('Parsed JSON:', data);
        if (data.status === 'success') {
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('JSON Parse Error:', error);
        showAlert('An error occurred. Check console.', 'danger');
    });
}


</script>

<!-- Update the Modal Script -->
<script>
$(document).ready(function() {
    const form = $('#profileForm');
    const saveBtn = $('#saveProfileBtn');
    const modal = $('#editProfileModal');
    const alertPlaceholder = $('#alertPlaceholder');

    function showAlert(message, type = 'success') {
        const alert = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        alertPlaceholder.html(alert);
    }

    saveBtn.on('click', function() {
        // Check if user is logged in
        if (!<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
            showAlert('Please log in to update your profile', 'danger');
            return; // Stop execution if not logged in
        }

        // Proceed with the update
        updateProfile();
    });

    // Reset form and alerts when modal is closed
    modal.on('hidden.bs.modal', function() {
        form[0].reset();
        alertPlaceholder.empty();
    });
});
</script>

<!-- Add Debug Information -->
<div id="debug-info" style="display: none;">
    <p>Current Path: <span id="current-path"></span></p>
    <p>Update URL: <span id="update-url"></span></p>
</div>

<script>
// Debug information
$(document).ready(function() {
    $('#current-path').text(window.location.pathname);
    $('#update-url').text(window.location.pathname.replace('index.php', 'update_profile.php'));
    
    // Log server paths
    console.log('Current path:', window.location.pathname);
    console.log('Update URL:', window.location.pathname.replace('index.php', 'update_profile.php'));
});
</script>

<script>
$(document).ready(function() {
    $('#saveProfileBtn').on('click', function() {
        // Check if user is logged in
        if (!<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>) {
            showAlert('Please log in to update your profile', 'danger');
            return; // Stop execution if not logged in
        }

        // Proceed with the update
        updateProfile();
    });

    // Function to show alerts
    function showAlert(message, type = 'success') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        const alertPlaceholder = $('#alertPlaceholder');
        alertPlaceholder.html(alertHtml);
        
        // Auto dismiss after 3 seconds
        setTimeout(function() {
            alertPlaceholder.find('.alert').alert('close');
        }, 3000);
    }
});
</script>
<script>
$(document).ready(function() {
    // Profile update function
    function updateProfile() {
        // Show loading state
        const saveBtn = $('#saveProfileBtn');
        saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        // Get form data
        const formData = {
            full_name: $('#full_name').val().trim(),
            mobile_no: $('#mobile_no').val().trim(),
            address: $('#address').val().trim()
        };

        // Validate form data
        if (!formData.full_name || !formData.mobile_no || !formData.address) {
            showAlert('Please fill in all required fields', 'danger');
            saveBtn.prop('disabled', false).html('Save Changes');
            return;
        }

        // Make AJAX request
        $.ajax({
            url: 'update_profile.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Debug log

                if (response && response.status === 'success') {
                    // Update UI
                    $('.profile-info h2').text(response.data.name);
                    $('#profile-phone').text(response.data.mobile);
                    $('#profile-address').text(response.data.address);
                    
                    // Show success message and close modal
                    showAlert(response.message, 'success');
                    $('#editProfileModal').modal('hide');
                } else {
                    // Show error message
                    showAlert(response.message || 'Failed to update profile', 'danger');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.log('Response Text:', xhr.responseText); // Debug log

                // Check if the response is empty
                if (xhr.responseText.trim() === '') {
                    showAlert('Received an empty response from the server', 'danger');
                } else {
                    // Attempt to parse the response as JSON
                    try {
                        const response = JSON.parse(xhr.responseText);
                        showAlert(response.message || 'An error occurred while updating the profile', 'danger');
                    } catch (e) {
                        showAlert('An error occurred while updating the profile. Invalid response format.', 'danger');
                    }
                }
            },
            complete: function() {
                // Reset button state
                saveBtn.prop('disabled', false).html('Save Changes');
            }
        });
    }

    // Function to show alerts
    function showAlert(message, type = 'success') {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `;
        
        const alertPlaceholder = $('#alertPlaceholder');
        alertPlaceholder.html(alertHtml);
        
        // Auto dismiss after 3 seconds
        setTimeout(function() {
            alertPlaceholder.find('.alert').alert('close');
        }, 3000);
    }

    // Attach event listener to save button
    $('#saveProfileBtn').on('click', updateProfile);
});
</script>


 
