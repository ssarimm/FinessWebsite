<?php
include('calorieintakegoalbackend.php'); // Include the backend logic for form handling and data retrieval

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorie Intake Goal</title>
    <style>
        /* Basic styling for the page */
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); }
        h2 { color: #4CAF50; text-align: center; }
        p { font-size: 16px; line-height: 1.6; }
        form { margin-top: 20px; }
        input, button { padding: 10px; margin: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { background-color: #4CAF50; color: white; cursor: pointer; }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
<div class="container">
    <h2>Set Your Calorie Intake Goal</h2>

    <?php if ($calorieGoal > 0): ?>
        <p>Your current daily calorie goal is: <b><?= $calorieGoal ?> calories</b>.</p>
        <p>Calories consumed today: <b><?= $totalCalories ?> calories</b>.</p>
        <p>You've achieved <b><?= round($percentageAchieved, 2) ?>%</b> of your goal.</p>

        <h3>Edit Your Goal</h3>
        <form action="calorieintakegoalbackend.php" method="post">
            <label for="new_goal">New Daily Calorie Goal:</label>
            <input type="number" id="new_goal" name="new_goal" min="1" required>
            <button type="submit">Update Goal</button>
        </form>
    <?php else: ?>
        <p>No calorie intake goal is set. Please set your goal.</p>
        <form action="calorieintakegoalbackend.php" method="post" class="goal-form">
            <label for="new_goal">Enter Your Calorie Intake Goal:</label>
            <input type="number" name="new_goal" id="new_goal" min="1" required>
            <button type="submit">Set Goal</button>
        </form>
        
    <?php endif; ?>
</div>
</body>
</html>
