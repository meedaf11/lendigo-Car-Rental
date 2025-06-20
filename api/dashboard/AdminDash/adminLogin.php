<?php
session_start();
require_once 'functions.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['loginBtn'])) {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    if (loginAdmin($identifier, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials or access denied.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <link rel="stylesheet" href="css/adminLogin.css">
  
</head>
<body>

  <form method="POST" action="">
    <h2>Admin Login</h2>

    <label for="identifier">Email or Username</label>
    <input type="text" name="identifier" id="identifier" required>

    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>

    <label><input type="checkbox" onclick="togglePassword()"> Show Password</label>

    <input type="submit" name="loginBtn" value="Login">

    <?php if ($error): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>
  </form>

  <script>
    function togglePassword() {
      const passwordInput = document.getElementById("password");
      passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
  </script>

</body>
</html>
