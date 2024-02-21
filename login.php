<?php
session_start();
if(isset($_SESSION['username'])){
    header("Location: welcome.php");
    exit();
}

include('connection.php');

if (isset($_POST['submit'])) {
    $username = $_POST['user'];
    $password = $_POST['pass'];

    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $result->num_rows;

    if($count > 0 && password_verify($password, $row["password"])) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['loggedin'] = true;
        header("Location: https://bramhais.github.io/Design-And-Develop-Gym-Website/");
        exit();
    } else {
        echo '<script>alert("Login failed. Invalid username or password!!"); window.location.href = "login.php";</script>';
        exit();
    }
}
?>

<?php 
include("connection.php");
include("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="1.css">
</head>
<body>
    <br><br>
    <div id="form">
        <h1 id="heading">Login Form</h1>
        <form name="form" action="login.php" method="POST" onsubmit="return isValid()">
            <label>Enter Username/Email: </label>
            <input type="text" id="user" name="user" required><br><br>
            <label>Password: </label>
            <input type="password" id="pass" name="pass" required><br><br>
            <input type="submit" id="btn" value="Login" name="submit">
        </form>
    </div>
    <script>
        function isValid() {
            var user = document.getElementById("user").value;
            if (user.trim() === "") {
                alert("Enter username or email id!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
