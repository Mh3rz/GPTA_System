<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if (registerUser($password)) {
        $_SESSION['registration_success'] = 'Registration successful. You can now log in.';
        header('Location: index.php');
        exit;
    }
}

function registerUser($password) {
    include 'db_connection.php';

    $hashedPassword = password_hash($password, PASSWORD_ARGON2I);

    $query = "INSERT INTO `password`(`pass`) VALUES (?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $hashedPassword);
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
    <form action="pass.php" method="post">
        <input type="password" name="password" placeholder="Password" required><br><br>
        <input type="submit" value="add">
    </form>
</body>
</html>
