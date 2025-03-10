<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Premium Fitness Club</title>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
      rel="stylesheet"
    />
    <style>
      /* Previous styles remain the same */
      body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #000;
        color: #fff;
        overflow: hidden;
      }

      .main-banner {
        position: relative;
        height: 100vh;
        display: block;
        align-items: center;
        justify-content: center;
      }

      #bg-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
      }

      .video-overlay {
        position: absolute;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.85));
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .form-container {
        background: rgba(255, 255, 255, 0.9);
        padding: 1.2rem;
        border-radius: 10px;
        width: 100%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        color: #000;
        margin: 15px auto;
        position: relative;
        z-index: 1;
        overflow: hidden;
      }

      .form-container h3 {
        margin-bottom: 0.8rem;
        font-size: 1.4rem;
      }

      .form-container input,
      .form-container select {
        width: 100%;
        padding: 8px;
        margin: 2px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 0.9rem;
        box-sizing: border-box;
      }

      .form-container button {
        margin-top: 6px;
        padding: 8px;
        background: #ff5722;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background 0.3s;
      }

      .form-container button:hover {
        background: #e64a19;
      }

      .form-container .toggle-link {
        display: block;
        margin: 8px auto;
        font-size: 0.8rem;
        color: #007bff;
        text-decoration: none;
      }

      .form-container .toggle-link:hover {
        text-decoration: underline;
      }

      .logo-container {
        position: absolute;
        top: 20px;
        width: 100%;
        text-align: center;
        z-index: 1;
      }

      .logo-container img {
        width: 150px;
        max-width: 90%;
        filter: drop-shadow(0 0 10px rgba(0, 0, 0, 0.5));
      }

      /* New styles for field-specific error messages */
      .form-group {
        margin-bottom: 8px;
        text-align: left;
        width: 100%;
      }

      .error-text {
        color: #f44336;
        font-size: 0.7rem;
        margin-top: 1px;
        display: block;
        text-align: left;
        min-height: 0.7em;
      }

      input.error, select.error {
        border-color: #f44336;
      }

      .logo-container {
        position: absolute;
        width: 100%;
        text-align: center;
        z-index: 2;
      }

      .logo-container img {
        width: 200px; /* Increase size */
        max-width: 100%;
        filter: drop-shadow(0 0 15px rgba(0, 0, 0, 0.7));
      }

      /* Add a container for links */
      .links-container {
        margin-top: 10px;
        padding-top: 5px;
        border-top: 1px solid #eee;  /* Optional: adds a subtle separator */
      }
    </style>
  </head>
  <body>
    <div class="main-banner">
  <video autoplay muted loop id="bg-video">
    <source src="./assets/images/gym-video.mp4" type="video/mp4" />
  </video>
  <div class="video-overlay">
    
    <div class="form-container animate_animated animate_fadeIn">
      <h3>Register</h3>
      <?php
          include 'connect.php';
          $errors = array();
          $fields = array('fullname' => '', 'email' => '', 'password' => '', 'confirm_password' => '', 
                         'address' => '', 'mobile' => '', 'gender' => '', 'dob' => '');

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
              foreach ($fields as $field => $value) {
                  $fields[$field] = isset($_POST[$field]) ? htmlspecialchars(trim($_POST[$field])) : '';
              }

              // Validation
              if (empty($fields['fullname'])) {
                  $errors['fullname'] = "Full name is required.";
              } elseif (!preg_match("/^[a-zA-Z\s]+$/", $fields['fullname'])) {
                  $errors['fullname'] = "Name can only contain letters and spaces.";
              }

              if (empty($fields['email'])) {
                  $errors['email'] = "Email is required.";
              } elseif (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
                  $errors['email'] = "Invalid email address.";
              }

              if (empty($fields['address'])) {
                  $errors['address'] = "Address is required.";
              }

              if (empty($fields['gender'])) {
                  $errors['gender'] = "Please select a gender.";
              }

              if (empty($fields['dob'])) {
                  $errors['dob'] = "Date of birth is required.";
              }

              if (empty($fields['mobile'])) {
                  $errors['mobile'] = "Mobile number is required.";
              } elseif (!preg_match("/^[1-9][0-9]{9}$/", $fields['mobile'])) {
                  $errors['mobile'] = "Mobile number must be 10 digits and cannot start with 0.";
              }

              if (empty($fields['password'])) {
                  $errors['password'] = "Password is required.";
              } elseif (strlen($fields['password']) < 6) {
                  $errors['password'] = "Password must be at least 6 characters long.";
              }

              if (empty($fields['confirm_password'])) {
                  $errors['confirm_password'] = "Please confirm your password.";
              } elseif ($fields['password'] !== $fields['confirm_password']) {
                  $errors['confirm_password'] = "Passwords do not match.";
              }

              if (empty($errors)) {
                $hashed_password = password_hash($fields['password'], PASSWORD_DEFAULT);
            
                // Start transaction to ensure both inserts succeed or none does
                $conn->begin_transaction();
                
                try {
                    // First insert into register table
                    $stmt1 = $conn->prepare("INSERT INTO register (full_name, address, mobile_no, gender, dob) 
                                            VALUES (?, ?, ?, ?, ?)");
                    
                    if (!$stmt1) {
                        throw new Exception("Error in register table query: " . $conn->error);
                    }
                    
                    $stmt1->bind_param("sssss", 
                        $fields['fullname'],
                        $fields['address'], 
                        $fields['mobile'], 
                        $fields['gender'], 
                        $fields['dob']
                    );
                    
                    if (!$stmt1->execute()) {
                        throw new Exception("Error inserting into register: " . $stmt1->error);
                    }
                    
                    // Get the last inserted ID from register table
                    $user_id = $conn->insert_id;
                    
                    // Modified login table insert with hardcoded enum value
                    $stmt2 = $conn->prepare("INSERT INTO login (user_id, email, password, role) VALUES (?, ?, ?, 'member')");
                    
                    if (!$stmt2) {
                        throw new Exception("Error in login table query: " . $conn->error);
                    }
                    
                    $stmt2->bind_param("iss", 
                        $user_id,
                        $fields['email'],
                        $hashed_password
                    );
                    
                    if (!$stmt2->execute()) {
                        throw new Exception("Error inserting into login: " . $stmt2->error);
                    }
                    
                    // If we get here, both inserts were successful
                    $conn->commit();
                    
                    // Set success message in session
                    $_SESSION['registration_success'] = "Account created successfully! Please login.";
                    
                    // Redirect to login page
                    header("Location: login2.php");
                    exit(); // Important to prevent further code execution
                    
                } catch (Exception $e) {
                    // If any error occurs, roll back the transaction
                    $conn->rollback();
                    $errors['general'] = "Registration failed: " . $e->getMessage();
                } finally {
                    // Only close statements if they were successfully created
                    if (isset($stmt1) && $stmt1 !== false) $stmt1->close();
                    if (isset($stmt2) && $stmt2 !== false) $stmt2->close();
                }
            }
            
          }
      ?>
     <form action="" method="POST">
            <div class="form-group">
              <input
                type="text"
                name="fullname"
                placeholder="Full Name"
                value="<?php echo $fields['fullname']; ?>"
                class="<?php echo isset($errors['fullname']) ? 'error' : ''; ?>"
                required
              />
              <span class="error-text"><?php echo isset($errors['fullname']) ? $errors['fullname'] : ''; ?></span>
            </div>

            <div class="form-group">
              <input
                type="email"
                name="email"
                placeholder="Email Address"
                value="<?php echo $fields['email']; ?>"
                class="<?php echo isset($errors['email']) ? 'error' : ''; ?>"
                required
              />
              <span class="error-text"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></span>
            </div>

            <div class="form-group">
              <input
                type="text"
                name="address"
                placeholder="Address"
                value="<?php echo $fields['address']; ?>"
                class="<?php echo isset($errors['address']) ? 'error' : ''; ?>"
                required
              />
              <span class="error-text"><?php echo isset($errors['address']) ? $errors['address'] : ''; ?></span>
            </div>

            <div class="form-group">
              <select name="gender" class="<?php echo isset($errors['gender']) ? 'error' : ''; ?>" required>
                <option value="" disabled <?php echo empty($fields['gender']) ? 'selected' : ''; ?>>Select Gender</option>
                <option value="male" <?php echo ($fields['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                <option value="female" <?php echo ($fields['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                <option value="other" <?php echo ($fields['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
              </select>
              <span class="error-text"><?php echo isset($errors['gender']) ? $errors['gender'] : ''; ?></span>
            </div>

            <div class="form-group">
              <input
                type="date"
                name="dob"
                placeholder="Date of Birth"
                value="<?php echo $fields['dob']; ?>"
                class="<?php echo isset($errors['dob']) ? 'error' : ''; ?>"
                required
              />
              <span class="error-text"><?php echo isset($errors['dob']) ? $errors['dob'] : ''; ?></span>
            </div>

            <div class="form-group">
              <input
                type="text"
                name="mobile"
                placeholder="Mobile Number (10 digits, can't start with 0)"
                value="<?php echo $fields['mobile']; ?>"
                class="<?php echo isset($errors['mobile']) ? 'error' : ''; ?>"
                required
              />
              <span class="error-text"><?php echo isset($errors['mobile']) ? $errors['mobile'] : ''; ?></span>
            </div>

            <div class="form-group">
              <input
                type="password"
                name="password"
                placeholder="Password"
                class="<?php echo isset($errors['password']) ? 'error' : ''; ?>"
                required
              />
              <span class="error-text"><?php echo isset($errors['password']) ? $errors['password'] : ''; ?></span>
            </div>

            <div class="form-group">
              <input
                type="password"
                name="confirm_password"
                placeholder="Confirm Password"
                class="<?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>"
                required
              />
              <span class="error-text"><?php echo isset($errors['confirm_password']) ? $errors['confirm_password'] : ''; ?></span>
            </div>

            <?php if (isset($errors['general'])): ?>
              <div class="error-text" style="text-align: center; margin-bottom: 10px;">
                <?php echo $errors['general']; ?>
              </div>
            <?php endif; ?>

            <button type="submit">Register</button>
          </form>

      <div class="links-container">
          <a href="login2.php" class="toggle-link">Already have an account? Login here</a>
          <a href="forgot-password.php" class="toggle-link">Forgot Password?</a>
          <a href="enhanced-gym-landing.php" class="toggle-link">Back to Website</a>
      </div>
    </div>
  </div>
</div>
</body>
<script>
  // Add this script right before the closing body tag
const validationRules = {
    fullname: {
        pattern: /^[a-zA-Z\s]+$/,
        minLength: 2,
        message: {
            required: "Full name is required.",
            pattern: "Name can only contain letters and spaces.",
            minLength: "Name must be at least 2 characters long."
        }
    },
    email: {
        pattern:/^[^\s][a-zA-Z0-9._%+-]+@[a-zA-Z-]+(\.[a-zA-Z]{2,})+$/,
        message: {
            required: "Email is required.",
            pattern: "Invalid email address."
        }
    },
    address: {
        minLength: 5,
        message: {
            required: "Address is required.",
            minLength: "Address must be at least 5 characters long."
        }
    },
    gender: {
        message: {
            required: "Please select a gender."
        }
    },
    dob: {
        message: {
            required: "Date of birth is required."
        }
    },
    mobile: {
    pattern: /^[6-9]\d{9}$/, // Starts with 6,7,8,9 and exactly 10 digits
    message: {
        required: "Mobile number is required.",
        pattern: "Mobile number must be exactly 10 digits and start with 6, 7, 8, or 9."
    }
},
    password: {
        minLength: 6,
        message: {
            required: "Password is required.",
            minLength: "Password must be at least 6 characters long."
        }
    },
    confirm_password: {
        message: {
            required: "Please confirm your password.",
            match: "Passwords do not match."
        }
    }
};

function validateField(input) {
    const field = input.name;
    const value = input.value.trim();
    const rules = validationRules[field];
    const errorSpan = input.nextElementSibling;
    
    // Remove existing error classes
    input.classList.remove('error');
    
    // Required check
    if (value === '') {
        input.classList.add('error');
        errorSpan.textContent = rules.message.required;
        return false;
    }
    
    // Pattern check
    if (rules.pattern && !rules.pattern.test(value)) {
        input.classList.add('error');
        errorSpan.textContent = rules.message.pattern;
        return false;
    }
    
    // Minimum length check
    if (rules.minLength && value.length < rules.minLength) {
        input.classList.add('error');
        errorSpan.textContent = rules.message.minLength;
        return false;
    }
    
    // Password match check
    if (field === 'confirm_password') {
        const password = document.querySelector('input[name="password"]').value;
        if (value !== password) {
            input.classList.add('error');
            errorSpan.textContent = rules.message.match;
            return false;
        }
    }
    
    // Clear error message if validation passes
    errorSpan.textContent = '';
    return true;
}

// Add event listeners to all form inputs
document.querySelectorAll('.form-group input, .form-group select').forEach(input => {
    ['input', 'blur', 'change'].forEach(eventType => {
        input.addEventListener(eventType, () => {
            validateField(input);
            
            // Special case for confirm password
            if (input.name === 'password') {
                const confirmPassword = document.querySelector('input[name="confirm_password"]');
                if (confirmPassword.value !== '') {
                    validateField(confirmPassword);
                }
            }
        });
    });
});

// Form submit validation
document.querySelector('form').addEventListener('submit', function(e) {
    let isValid = true;
    
    // Validate all fields
    this.querySelectorAll('.form-group input, .form-group select').forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        e.preventDefault();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const registerContainer = document.getElementById("register-container");

    registerContainer.addEventListener("scroll", function () {
        if (registerContainer.scrollTop + registerContainer.clientHeight >= registerContainer.scrollHeight) {
            console.log("Scrolled to the bottom!");
        }
    });
});

</script>
</html>