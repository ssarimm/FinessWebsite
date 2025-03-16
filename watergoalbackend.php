<?php
include('connect.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=Please log in to set your goal.");
    exit;
}

$useremail = $_SESSION['user']; // Get email from session

// Fetch UserID based on the email
$sql = "SELECT UserID FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $useremail); // Bind the email parameter
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['UserID']; // Store the UserID
} else {
    echo "User not found.";
    exit;
}

$stmt->close();

// Handle goal setting or updating
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['goal'])) {
        // Handling goal setting or updating
        $goal = $_POST['goal'];

        // Check if the user already has a goal set
        $sql = "SELECT * FROM waterintakegoal WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Goal already exists, update it
            $sql = "UPDATE waterintakegoal SET goal = ? WHERE userID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $goal, $user_id);
            $stmt->execute();
        } else {
            // Goal doesn't exist, insert a new one
            $sql = "INSERT INTO waterintakegoal (userID, goal) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $goal);
            $stmt->execute();
        }

        header("Location: watergoal.php");
        exit();
    } elseif (isset($_POST['new_goal'])) {
        // Handling goal update with a custom goal
        $new_goal = $_POST['new_goal'];

        // Update the user's water intake goal in the database
        $sql = "UPDATE waterintakegoal SET goal = ? WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $new_goal, $user_id);
        if ($stmt->execute()) {
            echo "Goal updated successfully!";
            header("Location: watergoal.php");
            exit();
        } else {
            echo "Error updating goal: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch user's water intake goal
$sql = "SELECT goal FROM waterintakegoal WHERE userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$goal = 0; // Default to 0 if no goal is set
if ($result->num_rows > 0) {
    // If goal exists, fetch the current goal
    $row = $result->fetch_assoc();
    $goal = $row['goal'];
}

$stmt->close();
?>
