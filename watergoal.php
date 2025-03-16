
<?php
include('watergoalbackend.php'); // Include the backend logic for form handling and data retrieval
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Intake Goal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        .goal-form {
            margin-top: 20px;
        }

        .goal-form input, .goal-form select, .goal-form button {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .goal-form button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .goal-form button:hover {
            background-color: #45a049;
        }

        .go-home-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .go-home-btn:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Set Your Water Intake Goal</h2>

    <?php
    // Displaying goal progress or setting a new goal
    if ($goal > 0) {
        echo "<p>Your current water intake goal is: <b>$goal glasses</b>.</p>";

        // Calculate today's water intake
    $today = date('Y-m-d');
    $sql = "SELECT SUM(amount) AS total_intake FROM dailywaterintake WHERE UserID = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result();

    $totalIntake = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalIntake = $row['total_intake'] ?? 0;
    }
        $remainingIntake = max($goal - $totalIntake, 0);
        $percentageAchieved = ($goal > 0) ? ($totalIntake / $goal) * 100 : 0;

        echo "<p>You've consumed <b>$totalIntake glasses</b> today.</p>";
        echo "<p>You have <b>$remainingIntake glasses</b> left to reach your goal.</p>";
        echo "<p>You've achieved <b>" . round($percentageAchieved, 2) . "%</b> of your goal today.</p>";

        // Form to change the goal
        echo '<h3>Edit Your Goal</h3>';
        echo '<form action="watergoalbackend.php" method="post" class="goal-form">';
        echo '<label for="new_goal">Enter New Goal (in glasses):</label>';
        echo '<input type="number" name="new_goal" id="new_goal" min="1" required>';
        echo '<button type="submit">Change Goal</button>';
        echo '</form>';
    } else {
        echo "<p>No water intake goal set. Please set your goal.</p>";
        ?>
        <form action="watergoalbackend.php" method="post" class="goal-form">
            <label for="goal">Select Your Daily Water Intake Goal:</label>
            <select name="goal" id="goal">
                <option value="8">8 Glasses</option>
                <option value="10">10 Glasses</option>
                <option value="12">12 Glasses</option>
                <option value="custom">Custom Goal</option>
            </select>

            <div id="custom-goal" style="display: none;">
                <label for="custom-goal-value">Enter Your Custom Goal (in glasses):</label>
                <input type="number" name="custom-goal-value" id="custom-goal-value" min="1">
            </div>

            <button type="submit">Set Goal</button>
        </form>
        <?php
    }
    ?>

    <a href="index.php" class="go-home-btn">Go to Homepage</a>

</div>

<script>
    const goalDropdown = document.getElementById('goal');
    const customGoalDiv = document.getElementById('custom-goal');

    goalDropdown.addEventListener('change', function() {
        if (this.value === 'custom') {
            customGoalDiv.style.display = 'block';
        } else {
            customGoalDiv.style.display = 'none';
        }
    });
</script>

</body>
</html>
