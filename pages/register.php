<?php
// register.php - Simple user registration
include 'connect.php';

$error = '';
$success = '';

// Handle form submission
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Check if fields are empty
    if (empty($email) || empty($password) || empty($confirmPassword)) {
        $error = 'Please fill all fields';
    } 
    // Check if passwords match
    elseif ($password != $confirmPassword) {
        $error = 'Passwords do not match';
    } 
    // Check password length
    elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } 
    else {
        // Check if email already exists
        $check_email = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $check_email);
        
        if (mysqli_num_rows($result) > 0) {
            $error = 'Email already exists';
        } 
        else {
            // Hash password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insert = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')";
            
            if (mysqli_query($conn, $insert)) {
                $success = 'Account created successfully! Redirecting to login...';
                header("refresh:2;url=login.php");
            } else {
                $error = 'Registration failed. Please try again.';
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
<title>Sign Up - Yarnify</title>
<link rel="icon" href="yarnify.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

<style>
@import url('https://fonts.googleapis.com/css2?family=Lilita+One&family=Pacifico&display=swap');

*{
  font-family: "Lilita One", sans-serif;
  margin:0; 
  padding:0; 
  box-sizing:border-box;
}

body{
  background: linear-gradient(135deg, #fde2e4,#fff1c1,#e6f4ea);
}

.container{
  display:flex;
  justify-content:center;
  align-items:center;
  min-height:100vh;
  padding: 20px;
}

.form{
  background:white;
  padding:50px 40px;
  border-radius:10px;
  width:420px;
  max-width: 100%;
  display:flex;
  flex-direction:column;
  gap:20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.brand{
  font-family:"Pacifico", cursive;
  color:#f4b9e8;
  text-align:center;
  font-size:30px;
}

h2{
  text-align:center;
  color: #614c37;
}

/* INPUT GROUP */
.input-group{
  position:relative;
}

.input-group input{
  width:100%;
  margin-bottom:15px;
  padding:16px 45px 16px 16px;
  border:1px solid #ccc;
  border-radius:8px;
  outline:none;
  font-size:14px;
  transition:0.3s;
}

.input-group input:focus{
  border-color:#b6fdcf;
}

/* Eye icon */
.eye-icon{
  position:absolute;
  right:15px;
  top:50%;
  transform:translateY(-50%);
  cursor:pointer;
  color:#aaa;
}

/* Button */
button{
  padding:14px;
  border:none;
  border-radius:8px;
  background:#3e7cdf;
  color:white;
  font-size:16px;
  cursor:pointer;
  transition:0.3s;
}

button:hover{
  background:#1e5ef5;
}

/* Messages */
.error{
  color: #ff6b6b;
  font-size:14px;
  text-align:center;
  padding: 10px;
  background: #ffe0e0;
  border-radius: 6px;
}

.success{
  color: #5cdb95;
  font-size:14px;
  text-align:center;
  padding: 10px;
  background: #e0ffe0;
  border-radius: 6px;
}

a{
  color:#47a7f5;
  text-decoration:none;
}

a:hover {
  text-decoration: underline;
}

@media (max-width: 480px) {
  .form {
    padding: 40px 30px;
  }
}
</style>
</head>

<body>

<div class="container">
  <div class="form">
    <div class="brand">Yarnify</div>
    <h2>Create Account</h2>

    <?php if ($error): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
      <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST">
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required 
               value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
      </div>

      <div class="input-group">
        <input type="password" id="password" name="password" placeholder="Password (min 6 characters)" required>
        <span class="eye-icon" onclick="togglePassword('password', this)">
          <i class="far fa-eye"></i>
        </span>
      </div>

      <div class="input-group">
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
        <span class="eye-icon" onclick="togglePassword('confirmPassword', this)">
          <i class="far fa-eye"></i>
        </span>
      </div>

      <center><button type="submit" name="submit">Sign Up</button></center>
    </form>

    <p style="text-align:center;">
      Already have an account?
      <a href="login.php">Login</a>
    </p>
  </div>
</div>

<script>
// Toggle password visibility
function togglePassword(fieldId, icon){
  const input = document.getElementById(fieldId);

  if(input.type === "password"){
    input.type = "text";
    icon.innerHTML = `<i class="fa fa-eye-slash"></i>`;
  } else {
    input.type = "password";
    icon.innerHTML = `<i class="far fa-eye"></i>`;
  }
}
</script>

</body>
</html>