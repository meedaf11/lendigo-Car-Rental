<?php
require_once 'functions.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: adminLogin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/index.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { display: flex; font-family: Arial, sans-serif; }

    aside {
      width: 220px;
      height: 100vh;
      background-color: #1e1e2f;
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      padding: 20px;
    }

    aside h2 {
      margin-bottom: 25px;
      font-size: 22px;
    }

    aside ul {
      list-style: none;
    }

    aside ul li {
      margin: 10px 0;
    }

    aside ul li a {
      color: #ccc;
      text-decoration: none;
      display: block;
      padding: 10px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    aside ul li a:hover,
    aside ul li a.active {
      background-color: #333b55;
      color: #fff;
    }

    .content {
      margin-left: 220px;
      padding: 30px;
      width: calc(100% - 220px);
      min-height: 100vh;
      background-color: #f9f9f9;
    }
  </style>
</head>
<body>

  <aside>
    <h2>Admin Panel</h2>
    <ul>
      <li><a href="?page=statistics" class="<?= ($_GET['page'] ?? '') === 'statistics' ? 'active' : '' ?>">Statistics</a></li>
      <li><a href="?page=users" class="<?= ($_GET['page'] ?? '') === 'users' ? 'active' : '' ?>">Users</a></li>
      <li><a href="?page=agencies" class="<?= ($_GET['page'] ?? '') === 'agencies' ? 'active' : '' ?>">Agencies</a></li>
      <li><a href="?page=cars" class="<?= ($_GET['page'] ?? '') === 'cars' ? 'active' : '' ?>">Cars</a></li>
      <li><a href="?page=profile" class="<?= ($_GET['page'] ?? '') === 'profile' ? 'active' : '' ?>">Admin Profile</a></li>
      <li><a href="?page=messages" class="<?= ($_GET['page'] ?? '') === 'messages' ? 'active' : '' ?>">Messages</a></li>
      <li><a href="../logout.php">Logout</a></li>
    </ul>
  </aside>

  <div class="content">
    <?php
      $page = $_GET['page'] ?? 'statistics';

      $allowed = ['statistics', 'users', 'agencies', 'cars', 'profile'];
      if (in_array($page, $allowed)) {
          include "$page.php";
      } else {
          echo "<h2>Page not found</h2>";
      }
    ?>
  </div>

</body>
</html>
