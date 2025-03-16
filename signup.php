<?php include ('connect.php');
session_start();
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            width: 350px;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        .form-container input,
        .form-container select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: white;
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

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>    
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form method="POST" action="signupBackend.php">
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" required>
            </div>
            <div class="form-group">
                <label for="dateOfBirth">Date of Birth:</label>
                <input type="date" id="dateOfBirth" name="dateOfBirth" required>
            </div>
            <div class="form-group">
                <label for="sex">Sex:</label>
                <select id="sex" name="sex" required>
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
