<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: index.php?error=Access Denied. You are not an admin.");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <div class="left">
        <img src="images/logo2.png" alt="logo" width="100">
    </div>
    <div class="mid">
        <ul class="navbar">
            <li><a href="index.php">Home</a></li>
            <li><a href="dashboard.php" class="active">Admin Dashboard</a></li>
            <li><a href="logoutBackend.php">Logout</a></li>
        </ul>
    </div>
</header>

<div class="dashboard-container">
    <h1>Welcome to the Admin Dashboard</h1>
    <p>Hello, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</p>
    <hr>

    <h2>Manage Users</h2>
    <p>Here you can manage users, view reports, or perform other administrative tasks.</p>
    <a href="adminmanage.php" class="btn">Manage Users</a>

    <?php if (isset($_SESSION['isSuperAdmin']) && $_SESSION['isSuperAdmin'] === true): ?>
        <hr>
        <h2>Super Admin Options</h2>
        <p>As a Super Admin, you can add new admins.</p>
        <a href="addadmin.php" class="btn">Add New Admin</a>
    <?php endif; ?>
</div>

</body>
</html>
