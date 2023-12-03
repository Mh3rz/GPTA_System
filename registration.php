<?php
session_start();
include("security_login.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (registerUser($username, $password)) {
        $_SESSION['registration_success'] = 'Registration successful. You can now log in.';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['registration_error'] = 'Registration failed. Please try again.';
        header('Location: registration.php');
        exit;
    }
}

function registerUser($username, $password) {
    $host = "localhost";
    $user = "root";
    $db_password = "";
    $dbname = "gpta";
    $conn = mysqli_connect($host, $user, $db_password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $hashedPassword = password_hash($password, PASSWORD_ARGON2I);

    $query = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_close($conn);

        return $result;
    } else {
        mysqli_close($conn);
        return false;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
</head>
<body>
    <h2>Registration</h2>
    <form action="registration.php" method="post">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>