<?php
include('connect.php'); // Include the database connection
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$useremail = $_SESSION['user'];

// Fetch UserID based on the email
$sql = "SELECT UserID FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userID = $row['UserID'];
} else {
    echo "User not found.";
    exit;
}

$stmt->close();

// Initialize calorieGoal variable
$calorieGoal = 0;

// Fetch current calorie goal
$sql = "SELECT caloriesPerDay FROM caloriegoal WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$goal = $result->fetch_assoc()['intakeCaloriesGoal'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newGoal = $_POST['new_goal'];

    if ($goal > 0) {
        // Update existing goal
        $sql = "UPDATE caloriegoal SET caloriesPerDay = ? WHERE UserID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newGoal, $userID);
    } else {
        // Insert new goal
        $sql = "INSERT INTO caloriegoal (UserID, caloriesPerDay) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userID, $newGoal);
    }
    $stmt->execute();
    $stmt->close();

    header("Location: workoutgoal.php");
    exit;
}


// Calculate today's calorie intake

$sql = "SELECT SUM(calories) AS totalCalories FROM dailymeal WHERE UserID = ? AND date = CURDATE();";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$totalCalories = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalCalories = $row['totalCalories'] ?? 0;
}

// Calculate the percentage of the goal achieved
$percentageAchieved = ($calorieGoal > 0) ? ($totalCalories / $calorieGoal) * 100 : 0;

$stmt->close();
$conn->close();
?>
