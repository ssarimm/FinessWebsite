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

// Handle GET request: Fetch water intake data
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT date, amount FROM dailywaterintake WHERE UserID = ? ORDER BY date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $intakes = [];
    while ($row = $result->fetch_assoc()) {
        $intakes[] = $row;
    }

    echo json_encode(['success' => true, 'intakes' => $intakes]);
    exit;
}

// Handle POST request: Insert new water intake
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    $waterIntake = $input['waterIntake'];  // The water intake value
    $date = $input['date'];  // The date of water intake
    
    // Validate input
    if (empty($waterIntake) || empty($date)) {
        echo json_encode(['success' => false, 'error' => 'Date and number of glasses are required']);
        exit;
    }

    // Insert data into the dailywaterintake table, handling duplicates
    $sql = "
    INSERT INTO dailywaterintake (UserID, amount, date) 
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        amount = amount + VALUES(amount)
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'Database query preparation failed: ' . $conn->error]);
        exit;
    }

    $stmt->bind_param("iss", $userID, $waterIntake, $date);  // Bind the correct data types

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database query execution failed: ' . $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
