<?php
require_once 'config.php'; // Includes session_start and DB
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../login.html");
    exit;
}

// Save agency_id from URL to session if provided
if (isset($_GET['agency_id'])) {
    $_SESSION['agency_id'] = (int) $_GET['agency_id'];
}
// Redirect if no agency_id in session
if (!isset($_SESSION['agency_id'])) {
    echo "<h2>Agency ID is missing. Please access the dashboard through the proper agency link.</h2>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agency Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Agency Panel</h2>
        <ul>
            <li><a href="?page=statistics">📊 Statistics</a></li>
            <li><a href="?page=cars">🚗 Cars</a></li>
            <li><a href="?page=bookings">📅 Bookings</a></li>
            <li><a href="?page=reviews">⭐ Reviews</a></li>
            <li><a href="?page=addBalance">💰 Add Balance</a></li>
            <li><a href="?page=settings">⚙️ Settings</a></li>
            <li><a href="../../../login.html">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Dynamic Content Area -->
    <div class="main-content">
        <?php
        $page = $_GET['page'] ?? 'statistics';
        $allowedPages = ['statistics', 'cars', 'bookings', 'reviews', 'settings', 'addBalance'];

        if (in_array($page, $allowedPages)) {
            include "pages/$page.php";
        } else {
            echo "<h2>Page not found.</h2>";
        }
        ?>
    </div>


</div>


<script>
    const agencyId = <?php echo json_encode($_SESSION['agency_id']); ?>;
    console.log("Agency ID from PHP:", agencyId);
</script>

</body>
</html>
