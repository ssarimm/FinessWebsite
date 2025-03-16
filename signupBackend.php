<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $dateOfBirth = $_POST["dateOfBirth"];
    $sex = $_POST["sex"];
    $email = $_POST["email"];
    $password=$_POST["password"];

    
    if (isset($_POST["password"]) && !empty($_POST["password"])) {
        
    } else {
        echo "Password cannot be empty.";
        exit(); 
    }

    
    $sql = "INSERT INTO users (FirstName, LastName, DateOfBirth, Sex, email, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

   
    $stmt->bind_param("ssssss", $firstName, $lastName, $dateOfBirth, $sex, $email, $password); 
    if ($stmt->execute()) {
       
        header("Location: login.php");
        exit(); 
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
