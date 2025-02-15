<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - I.T.T Group of Education</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      text-align: center;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    h2 {
      color: #002060;
      border-bottom: 4px solid #004080;
      display: inline-block;
      padding-bottom: 5px;
    }
    input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #004080;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #002060;
    }
    .links {
      margin-top: 10px;
    }
    .links a {
      color: #004080;
      text-decoration: none;
      font-weight: bold;
      cursor: pointer;
    }
    .links a:hover {
      color: #ffcc00;
    }
    input[type="file"] {
      padding: 3px;
    }
  </style>
  <script>
    function showSignUp() {
      document.getElementById('login-form').style.display = 'none';
      document.getElementById('signup-form').style.display = 'block';
      document.getElementById('forgot-form').style.display = 'none';
    }
    function showLogin() {
      document.getElementById('signup-form').style.display = 'none';
      document.getElementById('login-form').style.display = 'block';
      document.getElementById('forgot-form').style.display = 'none';
    }
    function showForgotPassword() {
      document.getElementById('login-form').style.display = 'none';
      document.getElementById('signup-form').style.display = 'none';
      document.getElementById('forgot-form').style.display = 'block';
    }
  </script>
</head>
<body>
<?php 
  require_once "header.php"; 
  echo getMyHeader();
 ?>
  
  <!-- Login Form -->
  <div class="container" id="login-form">
    <h2>Login</h2>
    <form action="../backend/login.php" method="POST">
      <input type="text" name="email_or_phone" placeholder="Email or Phone" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <div class="links">
      <p><a onclick="showForgotPassword()">Forgot Password?</a></p>
      <p><a onclick="showSignUp()">New User? Sign Up</a></p>
    </div>
  </div>
  
  <!-- Sign Up Form -->
  <div class="container" id="signup-form" style="display: none;">
    <h2>Sign Up</h2>
    <form action="../backend/register.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="full_name" placeholder="Full Name" required>
      <input type="text" name="father_name" placeholder="Father's Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="phone" placeholder="Phone Number" required>
      <input type="date" name="dob" placeholder="Date of Birth" required>
      <input type="text" name="user_class" placeholder="" required>
      <label>Upload Photo:</label>
      <input type="file" name="photo" accept="image/*" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Sign Up</button>
    </form>
    <div class="links">
      <p>Already have an account? <a onclick="showLogin()">Login</a></p>
    </div>
  </div>
  
  <!-- Forgot Password Form -->
  <div class="container" id="forgot-form" style="display: none;">
    <h2>Forgot Password</h2>
    <form>
      <input type="text" placeholder="Enter Email or Phone" required>
      <button type="submit">Reset Password</button>
    </form>
    <div class="links">
      <p><a onclick="showLogin()">Back to Login</a></p>
    </div>
  </div>
  <?php 
    require_once 'footer.php';
    echo getFooter();
  ?>
  <script src="script.js"></script>
</body>
</html>
