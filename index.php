<?php
session_start();

$isLoggedIn = isset($_SESSION['user']); 

$isAdmin = $_SESSION['isAdmin'] ?? false; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Club</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="left">
            <img src="images/logo2.png" alt="logo" width="100">
        </div>
        <div class="mid">
            <ul class="navbar">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="#workouts">Workouts</a></li>
                <li><a href="bmi.php">Fitness Calculator</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#contact">Contact</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="right">
            <?php if ($isLoggedIn): ?>
                 <span>Welcome, <?php echo htmlspecialchars($_SESSION['firstName']); ?>!</span>
                <button id="logout-btn" class="btn">
                    <a href="logoutBackend.php" style="text-decoration:none">Logout</a>
                </button>
            <?php else: ?>
               <button id="signup-btn" class="btn">
                    <a href="signup.php" style="text-decoration:none">Signup</a>
                </button>
                <button id="login-btn" class="btn">
                    <a href="login.php" style="text-decoration:none">Login</a>
                </button>
            <?php endif; ?>
        </div>
    </header>

    <div class="main-content">
        <h1 class="main-heading">Welcome to Fitness Club</h1>
        <p class="main-paragraph">"Unleash Your Inner Strength. Start Today!"</p>
        <hr class="green-hr">
    </div>

    <div id="workouts" class="workout-section">
        <h1>Workout Tracker</h1>
        <div class="workout-boxes" id="flex">
            <div class="workout-box">
                <i class="fas fa-dumbbell"></i>
                <h3>Flex Muscle</h3>
                <p>Temporarily contract and strengthen your muscles.</p>
            </div>
            <div class="workout-box" id="cardio">
                <i class="fas fa-heartbeat"></i>
                <h3>Cardio Exercise</h3>
                <p>Boost your heart rate and endurance.</p>
            </div>
            <div class="workout-box" id="yoga">
                <i class="fas fa-spa"></i>
                <h3>Basic Yoga</h3>
                <p>Enhance flexibility and mindfulness.</p>
            </div>
            <div class="workout-box" id="lifting" >
                <i class="fas fa-weight"></i>
                <h3>Weight Lifting</h3>
                <p>Build strength with targeted lifts.</p>
            </div>
        </div>
        <button id="workout-trackerbtn" class="btn"><a href='dailyworkout.php'>Track Now</a></button>
    </div>

    <div class="calorie-tracker">
        <div class="text-calorie-container">
          <h2>Calorie Tracker</h2>
          <p>Monitor your daily calorie intake and expenditure.</p>
          <button id="calorie-trackerbtn" class="btn"><a href='dailycalorieintake.php'>Track Now</a></button>
        </div>
        <div class="image-calorie-container">
          <img src="images/calorie.jpg" alt="Calorie Tracker Image">
        </div>
    </div>

    <div class="water-tracker">
    <div class="text-water-container">
        <h2>Water Tracker</h2>
        <p>Keep track of your daily water intake to stay hydrated and healthy.</p>
        <button id="water-trackerbtn" class="btn"><a href='dailywaterintake.php'>Track Now</a></button>
    </div>
    <!-- <div class="image-water-container">
        <img src="images/water.jpg" alt="Water Tracker Image">
    </div> -->
</div>


    

    <div id="goals" class="goals-section">
  <h2>Set Your Daily Goals</h2>
  <div class="goals">
    <div class="goal">
      <i class="fas fa-tint"></i>
      <h3>Water Intake</h3>
      <p>Stay hydrated, stay healthy!</p>
      <button class="btn"><a href='watergoal.php'>Set Goal</a></button>
    </div>

    <div class="goal">
      <i class="fas fa-dumbbell"></i>
      <h3>Workout Goal</h3>
      <p>Get moving, feel good!</p>
      <button class="btn"><a href='workoutgoal.php'>Set Goal</a></button>
    </div>

    <div class="goal">
      <i class="fas fa-apple-alt"></i>
      <h3>Calorie Intake</h3>
      <p>Fuel your body, reach your goals!</p>
      <button class="btn"><a href='calorieintakegoal.php'>Set Goal</a></button>
    </div>
  </div>
</div>



<script src="script.js"></script>
</body>
</html>
