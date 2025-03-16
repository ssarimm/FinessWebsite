<?php
include('connect.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$useremail = $_SESSION['user']; // Get email from session

// Fetch UserID based on the email
$sql = "SELECT UserID FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$userID = $result->fetch_assoc()['UserID'] ?? null;
$stmt->close();

// Get the current goal
$sql = "SELECT netCaloriesGoal FROM trainingGoal WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$goal = $result->fetch_assoc()['netCaloriesGoal'] ?? 0;
$stmt->close();

// Handle form submission to set/update the goal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newGoal = $_POST['new_goal'];

    if ($goal > 0) {
        // Update existing goal
        $sql = "UPDATE trainingGoal SET netCaloriesGoal = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newGoal, $userID);
    } else {
        // Insert new goal
        $sql = "INSERT INTO trainingGoal (UserID, netCaloriesGoal) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userID, $newGoal);
    }
    $stmt->execute();
    $stmt->close();

    header("Location: workoutgoal.php");
    exit;
}

// Calculate progress from dailyworkouts

$sql = "SELECT SUM(caloriesBurned) AS total_burned FROM dailyworkouts WHERE UserID = ? AND date = CURDATE();";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$totalBurned = $result->fetch_assoc()['total_burned'] ?? 0;
$stmt->close();

$conn->close();
?>
