<?php

include('db_connection.php');

class LogoutHandler
{
    private $dbConnection;

    public function __construct($dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function logout()
    {
        $this->destroySession();
        $this->redirectToIndex();
    }

    private function destroySession()
    {
        session_start();
        unset($_SESSION['username']);
        session_destroy();
    }

    private function redirectToIndex()
    {
        header('location: index.php');
        exit;
    }
}

// Usage
$logoutHandler = new LogoutHandler($conn);
$logoutHandler->logout();

?>
