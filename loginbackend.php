<?php
include('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Check if email or password fields are empty
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=All fields are required.");
        exit;
    }

    // Query to fetch user details
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if password matches
        if ($password === $user['password']) { // Consider using password_hash and password_verify for security
            $_SESSION['user'] = $user['email'];
            $_SESSION['firstName'] = $user['FirstName'];

            // Check if the user is an admin
            $adminQuery = "SELECT * FROM admin WHERE email = ?";
            $adminStmt = $conn->prepare($adminQuery);
            $adminStmt->bind_param('s', $email);
            $adminStmt->execute();
            $adminResult = $adminStmt->get_result();

            if ($adminResult->num_rows > 0) {
                $_SESSION['isAdmin'] = true;

                // Check if the user is a super admin
                $superAdminQuery = "SELECT * FROM admin WHERE email = ? AND role = 'superadmin'";
                $superAdminStmt = $conn->prepare($superAdminQuery);
                $superAdminStmt->bind_param('s', $email);
                $superAdminStmt->execute();
                $superAdminResult = $superAdminStmt->get_result();

                if ($superAdminResult->num_rows > 0) {
                    $_SESSION['isSuperAdmin'] = true;
                } else {
                    $_SESSION['isSuperAdmin'] = false;
                }

                $superAdminStmt->close();
            } else {
                $_SESSION['isAdmin'] = false;
                $_SESSION['isSuperAdmin'] = false;
            }

            $adminStmt->close();
        } else {
            // Invalid password
            header("Location: login.php?error=Invalid password.");
            exit;
        }
    } else {
        // User not found
        header("Location: login.php?error=User not found.");
        exit;
    }

    $stmt->close();
    header("Location: index.php");
    exit;
}
?>
