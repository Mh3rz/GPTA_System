<?php

session_start();
include "db_connection.php";

class UserAuthentication
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function authenticateUser($username, $password)
    {
        $hashedPassword = $this->getHashedPassword($username);

        if ($hashedPassword === null) {
            return 'UserNotFound'; // User doesn't exist
        }

        if (password_verify($password, $hashedPassword)) {
            return 'Success'; // Authentication successful
        } else {
            return 'InvalidPassword'; // Authentication failed
        }
    }

    private function getHashedPassword($username)
    {
        $query = "SELECT password FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $hashedPassword);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $hashedPassword;
    }
}

class LoginHandler
{
    private $userAuthentication;

    public function __construct(UserAuthentication $userAuthentication)
    {
        $this->userAuthentication = $userAuthentication;
    }

    public function handleLogin($username, $password)
    {
        $loginResult = $this->userAuthentication->authenticateUser($username, $password);

        if ($loginResult === 'Success') {
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $this->handleLoginError($loginResult);
        }
    }

    private function handleLoginError($errorType)
    {
        switch ($errorType) {
            case 'DBError':
                $_SESSION['login_error'] = 'Unable to connect to the database. Please try again later.';
                break;
            case 'UserNotFound':
            case 'InvalidPassword':
                $_SESSION['login_error'] = 'Invalid username or password';
                break;
            // Add more cases as needed

            default:
                $_SESSION['login_error'] = 'An unexpected error occurred. Please try again later.';
                break;
        }

        header('Location: index.php');
        exit;
    }
}

// Usage
$userAuthentication = new UserAuthentication($conn);
$loginHandler = new LoginHandler($userAuthentication);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $loginHandler->handleLogin($username, $password);
}

?>
