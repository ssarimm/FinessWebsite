<?php
include('connect.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
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
    $userID = $row['UserID']; // Store the UserID
} else {
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit;
}

$stmt->close();

// Handle GET request: Fetch calorie intake data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT date, calories FROM dailymeal WHERE UserID = ? ORDER BY date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $meals = [];
    while ($row = $result->fetch_assoc()) {
        $meals[] = $row;
    }

    echo json_encode(['success' => true, 'meals' => $meals]);
    exit;
}

// Handle POST request: Insert new meal data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    $mealName = $input['mealName'];  // The meal name
    $mealDate = $input['mealDate'];  // The meal date
    $calories = $input['calories'];  // The calories value
    $mealDescription = $input['mealDescription'];  // The meal description
    
    // Validate input
    if (empty($mealName) || empty($mealDate) || empty($calories) || empty($mealDescription)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit;
    }

    // Insert data into the dailycalorieintake table
    $sql = "
    INSERT INTO dailymeal (UserID, mealName, calories, mealDescription, date) 
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
            calories=calories+VALUES(calories)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Database query preparation failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("issss", $userID, $mealName, $calories, $mealDescription, $mealDate);  // Bind the correct data types

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database query execution failed: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
