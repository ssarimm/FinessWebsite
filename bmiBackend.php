<?php
include('connect.php'); 
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=Please log in to calculate your BMI.");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    if (empty($height) || empty($weight) || $height <= 0 || $weight <= 0) {
        header("Location: bmi.php?error=Invalid height or weight.");
        exit;
    }

    $userEmail = $_SESSION['user'];

    $query = "SELECT userID FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userID = $user['userID'];

        $insertQuery = "INSERT INTO userHealth (userID, height, weight) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('idd', $userID, $height, $weight);

        if ($insertStmt->execute()) {
            $bmi = $weight / (($height / 100) ** 2);
            $comment = "";

            if ($bmi < 18.5) {
                $comment = "Underweight";
            } elseif ($bmi < 24.9) {
                $comment = "Normal weight";
            } elseif ($bmi < 29.9) {
                $comment = "Overweight";
            } else {
                $comment = "Obesity";
            }

            header("Location: bmi.php?bmi=" . urlencode(number_format($bmi, 2)) . "&comment=" . urlencode($comment));
            exit;
        } else {
            header("Location: bmi.php?error=Failed to save BMI data. Please try again.");
            exit;
        }

        $insertStmt->close();
    } else {
        header("Location: login.php?error=User not found. Please log in again.");
        exit;
    }

    $stmt->close();
} else {
    header("Location: bmi.php");
    exit;
}
?>
