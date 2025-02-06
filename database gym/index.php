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
                                    <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                                    <li class="scroll-to-section"><a href="#features">profile</a></li>
                                    <li class="scroll-to-section"><a href="#our-classes">Classes</a></li>
                                    <li class="scroll-to-section"><a href="#schedule">Schedules</a></li>
                                    <li class="scroll-to-section"><a href="#contact-us">Contact</a></li> 
                                    <li class="main-button"><a href="login2.php">Sign Up</a></li>
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
        
        <div class="container" id="features">
          <div class="row">
              <div class="col-lg-6 offset-lg-3">
                  <div class="section-heading">
                      <h2>My <em>Profile</em></h2>
                      <img src="assets/images/line-dec.png" alt="">
                      <p>Nunc urna sem, laoreet ut metus id, aliquet consequat magna. Sed viverra ipsum dolor, ultricies fermentum massa consequat eu.</p>
                  </div>
              </div>
          </div>
        <section  class="profile-section hidden">
            <div class="profile-header">
              <div style="display: flex; align-items: center">
                <div class="profile-avatar">JD</div>
                <div class="profile-info">
                  <h2>John Doe</h2>
                  <p>Member since January 2024</p>
                </div>
              </div>
              <div class="profile-actions">
                <button class="edit-button">Edit Profile</button>
              </div>
            </div>
      
            <!-- <div class="profile-nav"> -->
              <!-- <div class="profile-nav-item active">Overview</div> -->
              <!-- <div class="profile-nav-item active">Workout Plans</div> -->
              <!-- <div class="profile-nav-item active">Progress</div> -->
              <!-- <div class="profile-nav-item active">Settings</div> -->
            <!-- </div> -->
      
            <div class="profile-content">
              <div class="profile-card">
                <h4>Personal Information</h4>
                <p><strong>Email:</strong> john.doe@example.com</p>
                <p><strong>Phone:</strong> +1 234 567 8900</p>
                <p><strong>Address:</strong> 123 Fitness Street</p>
              </div>
      
              <div class="profile-card">
                <h4>Membership Details</h4>
                <p><strong>Plan:</strong> Premium</p>
                <p><strong>Status:</strong> Active</p>
                <p><strong>Next Payment:</strong> Feb 1, 2024</p>
              </div>
      
              <div class="profile-card">
                <h4>Fitness Stats</h4>
                <div class="stat-grid">
                  <div class="stat-item">
                    <div class="stat-value">24</div>
                    <div class="stat-label">Workouts</div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Classes</div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-value">85%</div>
                    <div class="stat-label">Goal Progress</div>
                  </div>
                </div>
              </div>
            </div>
          </section>
      
          <script>
            // Previous script content remains
            const loginSection = document.getElementById("login");
            const registerSection = document.getElementById("register");
            const profileSection = document.getElementById("profile");
            const toggleLinks = document.querySelectorAll(".toggle-link");
            const navButtons = document.querySelectorAll(".nav-button");
            const logoutButton = document.querySelector(".logout-button");
            const loginForm = document.querySelector("#login form");
            const profileNavItems = document.querySelectorAll(".profile-nav-item");
      
            // Handle login form submission
            loginForm.addEventListener("submit", (e) => {
              e.preventDefault();
              loginSection.classList.add("hidden");
              profileSection.classList.remove("hidden");
            });
      
            // Handle logout
            logoutButton.addEventListener("click", () => {
              profileSection.classList.add("hidden");
              loginSection.classList.remove("hidden");
            });
      
            // Handle profile navigation
            profileNavItems.forEach((item) => {
              item.addEventListener("click", () => {
                profileNavItems.forEach((nav) => nav.classList.remove("active"));
                item.classList.add("active");
              });
            });
      
            // Previous script functionality remains
            toggleLinks.forEach((link) => {
              link.addEventListener("click", (e) => {
                e.preventDefault();
                loginSection.classList.toggle("hidden");
                registerSection.classList.toggle("hidden");
              });
            });
          </script>
    </section>
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
    
    <section class="section" id="schedule">
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
    </section>

    <!-- ***** Testimonials Starts ***** -->
    <section class="section" id="trainers">
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
            <img src="basic-membership.jpg" alt="Basic Membership" style=" color:white; width:50%; height: 100%; border-radius: 10px;">
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
      
          <div class="membership-card" >
            <img src="premium-membership.jpg" alt="Premium Membership" style=" color:white; width: 50%; height: 100%; border-radius: 10px;">
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
            <img src="vip-membership.jpg" alt="VIP Membership" style="color:white; width: 50%; height: 100%; border-radius: 10px;">
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
    </footer> -->

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

  </body>
</html>