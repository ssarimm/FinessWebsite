<?php
include('connect.php');
session_start();

// Get logged-in user ID (assuming it's stored in session)
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

// Handle POST request for adding new workout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    $workoutName = $input['workoutName'];
    $workoutDate = $input['workoutDate'];
    $startTime = $input['startTime'];
    $endTime = $input['endTime'];
    $duration = $input['duration'];
    $caloriesBurned = $input['caloriesBurned'];

    // Validate input
    if (empty($workoutName) || empty($workoutDate) || empty($startTime) || empty($endTime) || empty($duration) || empty($caloriesBurned)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        exit;
    }

   // Insert or Update data in the DailyWorkouts table
$sql = "
INSERT INTO DailyWorkouts (UserID, WorkoutName, startTime, endTime, WorkoutDuration, caloriesBurned, date) 
VALUES (?, ?, ?, ?, ?, ?, ?)
ON DUPLICATE KEY UPDATE 
    caloriesBurned = caloriesBurned + VALUES(caloriesBurned),
    WorkoutDuration = WorkoutDuration + VALUES(WorkoutDuration),
    startTime = LEAST(startTime, VALUES(startTime)),
    endTime = GREATEST(endTime, VALUES(endTime)),
    WorkoutName = CONCAT_WS(', ', WorkoutName, VALUES(WorkoutName))
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
echo json_encode(['success' => false, 'error' => 'Database query preparation failed: ' . $conn->error]);
exit;
}

$stmt->bind_param(
"isssids", 
$userID, 
$workoutName, 
$startTime, 
$endTime, 
$duration, 
$caloriesBurned, 
$workoutDate
);

if ($stmt->execute()) {
echo json_encode(['success' => true]);
} else {
echo json_encode(['success' => false, 'error' => 'Database query execution failed: ' . $stmt->error]);
}

$stmt->close();

}

// Fetch workouts for the logged-in user
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT date, caloriesBurned FROM DailyWorkouts WHERE UserID = ? ORDER BY date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID); // Bind the UserID parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Collect data for the graph
    $workouts = [];
    while ($row = $result->fetch_assoc()) {
        $workouts[] = $row;
    }

    echo json_encode(['success' => true, 'workouts' => $workouts]);

    $stmt->close();
}

$conn->close();
?>
