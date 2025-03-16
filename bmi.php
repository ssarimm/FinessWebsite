<?php
include('connect.php');
session_start(); 


if (!isset($_SESSION['user'])) {
    header("Location: login.php?error=Please log in to access the BMI Calculator.");
    exit;
}

$bmi = isset($_GET['bmi']) ? $_GET['bmi'] : null;
$comment = isset($_GET['comment']) ? $_GET['comment'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bmi.css">
    <title>BMI Calculator</title>
</head>
<body>
    <div class="container">
        <div class="box">
            <h1>BMI Calculator</h1>
            <div class="content">
                <form method="POST" action="bmiBackend.php">
                    <div class="input">
                        <label for="age">Age</label>
                        <input type="text" class="text-input" id="age" name="age" autocomplete="off" required />
                    </div>

                    <div class="gender">
                        <label class="gendermale">
                            <input type="radio" name="gender" value="male" id="m" required>
                            <p class="text">Male</p>
                        </label>
                        <label class="genderfemale">
                            <input type="radio" name="gender" value="female" id="f" required>
                            <p class="text">Female</p>
                        </label>
                    </div>

                    <div class="containerHW">
                        <div class="inputH">
                            <label for="height">Height (cm)</label>
                            <input type="number" id="height" name="height" required>
                        </div>

                        <div class="inputW">
                            <label for="weight">Weight (kg)</label>
                            <input type="number" id="weight" name="weight" required>
                        </div>
                    </div>

                    <button class="calculate" type="submit">Calculate BMI</button>
                </form>
            </div>

            <?php if ($bmi !== null): ?>
                <div class="result">
                    <p>Your BMI is</p>
                    <div id="result"><?php echo number_format($bmi, 2); ?></div>
                    <p class="comment"><?php echo htmlspecialchars($comment); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
