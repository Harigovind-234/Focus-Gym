<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile</title>
  <style>
    .hidden {
      display: none;
    }

    .profile-avatar {
      width: 50px;
      height: 50px;
      background-color: #007BFF;
      color: white;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
    }

    .profile-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      border-bottom: 1px solid #ccc;
    }

    .profile-card {
      margin: 20px 0;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
    }

    .form-group input, .form-group textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .profile-actions button {
      margin-left: 10px;
      padding: 5px 10px;
      cursor: pointer;
      border: none;
      border-radius: 5px;
    }

    .edit-button {
      background-color: #28a745;
      color: white;
    }

    .logout-button {
      background-color: #dc3545;
      color: white;
    }

    .save-button {
      background-color: #007BFF;
      color: white;
    }

    .cancel-button {
      background-color: #6c757d;
      color: white;
    }
  </style>
</head>
<body>
  <section class="profile-section">
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
        <button class="logout-button" onclick="location.href='login2.php'">Logout</button>
      </div>
    </div>

    <div class="profile-content">
      <!-- View Mode -->
      <div class="profile-card view-mode">
        <h4>Personal Information</h4>
        <p><strong>Email:</strong> <span id="email-view">john.doe@example.com</span></p>
        <p><strong>Phone:</strong> <span id="phone-view">+1 234 567 8900</span></p>
        <p><strong>Address:</strong> <span id="address-view">123 Fitness Street</span></p>
        <p><strong>Fitness Goals:</strong> <span id="goals-view">123 Fitness goals</span></p>
        <p><strong>Personal Quote:</strong> <span id="quote-view">quote</span></p>
      </div>

      <!-- Edit Mode -->
      <div class="profile-card edit-mode hidden">
        <h4>Edit Personal Information</h4>
        <form id="edit-profile-form">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" value="john.doe@example.com">
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" value="+1 234 567 8900">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" value="123 Fitness Street">
          </div>
          <div class="form-group">
            <label for="goals">Fitness Goals</label>
            <textarea id="goals">123 Fitness goals</textarea>
          </div>
          <div class="form-group">
            <label for="quote">Personal Quote</label>
            <textarea id="quote">quote</textarea>
          </div>
          <div>
            <button type="button" class="save-button">Save</button>
            <button type="button" class="cancel-button">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <script>
    const editButton = document.querySelector('.edit-button');
    const saveButton = document.querySelector('.save-button');
    const cancelButton = document.querySelector('.cancel-button');
    const viewMode = document.querySelector('.view-mode');
    const editMode = document.querySelector('.edit-mode');

    // Toggle to Edit Mode
    editButton.addEventListener('click', () => {
      viewMode.classList.add('hidden');
      editMode.classList.remove('hidden');
    });

    // Save Changes
    saveButton.addEventListener('click', () => {
      const email = document.getElementById('email').value;
      const phone = document.getElementById('phone').value;
      const address = document.getElementById('address').value;
      const goals = document.getElementById('goals').value;
      const quote = document.getElementById('quote').value;

      document.getElementById('email-view').textContent = email;
      document.getElementById('phone-view').textContent = phone;
      document.getElementById('address-view').textContent = address;
      document.getElementById('goals-view').textContent = goals;
      document.getElementById('quote-view').textContent = quote;

      // Toggle back to View Mode
      viewMode.classList.remove('hidden');
      editMode.classList.add('hidden');
    });

    // Cancel Editing
    cancelButton.addEventListener('click', () => {
      editMode.classList.add('hidden');
      viewMode.classList.remove('hidden');
    });
  </script>
</body>
</html>
