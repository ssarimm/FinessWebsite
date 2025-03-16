<?php include ('connect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1f1c2c, #928dab);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #292b3d;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .form-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #9bcd66;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #9bcd668e;
        }
        .error-message {
            color: #ff6b6b;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <?php
       
        $error = $_GET['error'] ?? '';
        if ($error) {
            echo "<p class='error-message'>" . htmlspecialchars($error) . "</p>";
        }
        ?>

        <form method="POST" action="loginbackend.php">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
