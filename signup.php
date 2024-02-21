<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit();
}

include("connection.php");

if (isset($_POST['submit'])) {
    $username = $_POST['user'];
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $cpassword = $_POST['cpass'];

    if ($password !== $cpassword) {
        echo '<script>alert("Passwords do not match"); window.location.href = "signup.php";</script>';
        exit();
    }

    $stmt = $conn->prepare("SELECT username FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $count_user = $stmt->num_rows;
    $stmt->close();

    $stmt = $conn->prepare("SELECT email FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $count_email = $stmt->num_rows;
    $stmt->close();

    if ($count_user > 0) {
        echo '<script>alert("Username already exists"); window.location.href = "signup.php";</script>';
        exit();
    }

    if ($count_email > 0) {
        echo '<script>alert("Email already exists"); window.location.href = "signup.php";</script>';
        exit();
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hash);
    $result = $stmt->execute();
    $stmt->close();

    if ($result) {
        // Redirect to Google URL after successful signup
        header("Location: https://bramhais.github.io/Design-And-Develop-Gym-Website/");
        exit();
    } else {
        echo '<script>alert("Error occurred while signing up"); window.location.href = "signup.php";</script>';
        exit();
    }
}

include("navbar.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    

  

<link rel="stylesheet" href="1.css">
</head>
<body>
<div id="form">
    <h1 id="heading">SignUp Form</h1><br>
    <form name="form" action="signup.php" method="POST">
        <label>Enter Username: </label>
        <input type="text" id="user" name="user" required><br><br>
        <label>Enter Email: </label>
        <input type="email" id="email" name="email" required><br><br>
        <label>Create Password: </label>
        <input type="password" id="pass" name="pass" required><br><br>
        <label>Retype Password: </label>
        <input type="password" id="cpass" name="cpass" required><br><br>
        <input type="submit" id="btn" value="SignUp" name="submit"/>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
