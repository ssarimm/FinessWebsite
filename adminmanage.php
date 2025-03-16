<?php
session_start();
include('connect.php'); // Include your database connection file

// Check if the user is an admin
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: index.php?error=Access Denied. You are not an admin.");
    exit;
}

// Handle delete operation
if (isset($_GET['delete'])) {
    $userID = intval($_GET['delete']);

    // Delete user from the database
    $sqlDelete = "DELETE FROM users WHERE UserID = ?";
    $stmtDelete = $conn->prepare($sqlDelete);

    if ($stmtDelete === false) {
        die("Error preparing delete query: " . $conn->error);
    }

    $stmtDelete->bind_param("i", $userID);
    if ($stmtDelete->execute()) {
        header("Location: adminmanage.php?success=User deleted successfully");
        exit;
    } else {
        header("Location: adminmanage.php?error=Failed to delete user");
        exit;
    }
}

// Handle edit operation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $userID = intval($_POST['UserID']);
        $firstName = $_POST['FirstName'];
        $lastName = $_POST['LastName'];
        $dob = $_POST['DateOfBirth'];
        $sex = $_POST['Sex'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Update user details
        $sqlUpdate = "UPDATE users SET FirstName = ?, LastName = ?, DateOfBirth = ?, Sex = ?, email = ?, password = ? WHERE UserID = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);

        if ($stmtUpdate === false) {
            die("Error preparing update query: " . $conn->error);
        }

        $stmtUpdate->bind_param("ssssssi", $firstName, $lastName, $dob, $sex, $email, $password, $userID);
        if ($stmtUpdate->execute()) {
            header("Location: adminmanage.php?success=User details updated successfully");
            exit;
        } else {
            header("Location: adminmanage.php?error=Failed to update user details");
            exit;
        }
    }
}

// Fetch all users from the users table
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
if ($result === false) {
    die("Error fetching users: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>

        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_GET['success']); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Date of Birth</th>
                    <th>Sex</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form action="adminmanage.php" method="post">
                                <td><?= htmlspecialchars($row['UserID']); ?></td>
                                <td>
                                    <input type="text" name="FirstName" value="<?= htmlspecialchars($row['FirstName']); ?>">
                                </td>
                                <td>
                                    <input type="text" name="LastName" value="<?= htmlspecialchars($row['LastName']); ?>">
                                </td>
                                <td>
                                    <input type="date" name="DateOfBirth" value="<?= htmlspecialchars($row['DateOfBirth']); ?>">
                                </td>
                                <td>
                                    <select name="Sex">
                                        <option value="Male" <?= $row['Sex'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?= $row['Sex'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>">
                                </td>
                                <td>
                                    <input type="password" name="password" value="<?= htmlspecialchars($row['password']); ?>">
                                </td>
                                <td>
                                    <input type="hidden" name="UserID" value="<?= $row['UserID']; ?>">
                                    <button type="submit" name="update">Update</button>
                                </form>
                                <form action="adminmanage.php" method="get" style="display:inline;">
                                    <input type="hidden" name="delete" value="<?= $row['UserID']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                                </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
