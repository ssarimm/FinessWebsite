<?php
include('workoutgoalbackend.php'); // Include the backend logic for form handling and data retrieval

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Calorie Burn Goal</title>
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
        h2, p { text-align: center; color: #4CAF50; }
        .goal-form input, .goal-form button { padding: 10px; margin: 5px; border-radius: 5px; border: 1px solid #ccc; }
        .goal-form button { background-color: #4CAF50; color: white; cursor: pointer; }
        .goal-form button:hover { background-color: #45a049; }
        .go-home-btn { display: inline-block; padding: 10px 20px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 5px; text-align: center; }
        .go-home-btn:hover { background-color: #0b7dda; }
    </style>
</head>
<body>

<div class="container">
    <h2>Set Your Calorie Burn Goal</h2>

    <?php
    if ($goal > 0) {
        $remainingCalories = max($goal - $totalBurned, 0);
        $percentageAchieved = ($goal > 0) ? ($totalBurned / $goal) * 100 : 0;

        echo "<p>Your current calorie burn goal is: <b>$goal calories</b>.</p>";
        echo "<p>You've burned <b>$totalBurned calories</b> today.</p>";
        echo "<p>You have <b>$remainingCalories calories</b> left to reach your goal.</p>";
        echo "<p>You've achieved <b>" . round($percentageAchieved, 2) . "%</b> of your goal today.</p>";

        // Form to change the goal
        echo '<form action="workoutgoalbackend.php" method="post" class="goal-form">';
        echo '<label for="new_goal">Enter New Calorie Goal:</label>';
        echo '<input type="number" name="new_goal" id="new_goal" min="1" required>';
        echo '<button type="submit">Change Goal</button>';
        echo '</form>';
    } else {
        echo "<p>No calorie burn goal set. Please set your goal.</p>";
        ?>
        <form action="workoutgoalbackend.php" method="post" class="goal-form">
            <label for="new_goal">Enter Your Calorie Goal:</label>
            <input type="number" name="new_goal" id="new_goal" min="1" required>
            <button type="submit">Set Goal</button>
        </form>
        <?php
    }
    ?>

    <a href="index.php" class="go-home-btn">Go to Homepage</a>
</div>

</body>
</html>
