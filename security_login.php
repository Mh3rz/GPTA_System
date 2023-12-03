<?php

class SessionChecker
{
    public function checkLoggedIn()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['username'])) {
            $this->redirectUserToLogin();
        }
    }

    private function redirectUserToLogin()
    {
        // Redirect the user back to the login page
        if (!headers_sent()) {
            header('Location: index.php');
            exit;
        } else {
            // Handle the case where headers are already sent
            echo "Headers already sent. Redirect failed.";
        }
    }
}

// Usage
$sessionChecker = new SessionChecker();
$sessionChecker->checkLoggedIn();

?>
