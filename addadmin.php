<?php
session_start();
include('connect.php'); 


if (!isset($_SESSION['isSuperAdmin']) || $_SESSION['isSuperAdmin'] !== true) {
    header("Location: index.php?error=Access Denied. You are not a super admin.");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['makeAdmin'])) {
        $userIDs = $_POST['makeAdmin']; // Array of selected UserIDs

        foreach ($userIDs as $userID) {
            // Fetch user details from the users table
            $sql = "SELECT FirstName, LastName, email, password FROM users WHERE UserID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Insert into admins table
                $sqlInsert = "
                    INSERT INTO admin (adminName, email, role, dateAdded, password)
                    VALUES (?, ?, 'admin', NOW(), ?)
                ";
                $stmtInsert = $conn->prepare($sqlInsert);
                $adminName = $user['FirstName'] . ' ' . $user['LastName'];
                $stmtInsert->bind_param("sss", $adminName, $user['email'], $user['password']);
                $stmtInsert->execute();
                $stmtInsert->close();
            }
            $stmt->close();
        }
        header("Location: addadmin.php?success=Admins added successfully");
        exit;
    }
}

// Fetch all users from the users table
$sql = "SELECT UserID, FirstName, LastName, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admins</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add Admins</h1>

        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;"><?= htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>

        <form action="addadmin.php" method="post">
            <table border="1" cellspacing="0" cellpadding="10">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="makeAdmin[]" value="<?= $row['UserID']; ?>">
                                </td>
                                <td><?= $row['UserID']; ?></td>
                                <td><?= htmlspecialchars($row['FirstName']); ?></td>
                                <td><?= htmlspecialchars($row['LastName']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit">Make Selected Users Admins</button>
        </form>

        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
