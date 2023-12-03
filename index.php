<?php

class LoginPage
{
    private $sessionKey = 'login_error';

    public function __construct()
    {
        session_start();
    }

    public function renderHead()
    {
        echo '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Login</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
                    <link rel="shortcut icon" href="assets/log_icon.png" type="img/x-icon" />
                    <style>
                        body {
                            background-image: url("images/gpta_watermarks.png");
                            background-size: cover;
                            background-position: center;
                            min-height: 100vh;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        }
            
                        .container {
                            background-color: rgba(255, 255, 255, 0.8);
                            padding: 8px;
                            border-radius: 10px;
                            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                            width: 18rem;
                        }
                        .card {
                            border: none;
                            box-shadow: none;
                        }
                        .card-title {
                            text-align: center;
                        }
                    </style>
                </head>';
    }

    public function renderBody()
    {
        echo '<body>
                    <div class="container">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">GPTA System</h3>
                                <hr>';
        
        if (isset($_SESSION[$this->sessionKey])) {
            echo '<p style="color: red;">' . $_SESSION[$this->sessionKey] . '</p>';
            unset($_SESSION[$this->sessionKey]); // Clear the error message after displaying it
        }
        
        echo '<form id="login-form" method="POST" action="login_process.php">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i id="eyeIcon" class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Load JavaScript at the end of the body for performance optimization -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script>
            const passwordInput = document.getElementById("password");
            const togglePasswordButton = document.getElementById("togglePassword");
            const eyeIcon = document.getElementById("eyeIcon");

            togglePasswordButton.addEventListener("click", function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    eyeIcon.classList.remove("bi-eye");
                    eyeIcon.classList.add("bi-eye-slash");
                } else {
                    passwordInput.type = "password";
                    eyeIcon.classList.remove("bi-eye-slash");
                    eyeIcon.classList.add("bi-eye");
                }
            });
        </script>
    </body>
    </html>';
    }
    }

// Create an instance of the LoginPage class and render the HTML
$loginPage = new LoginPage();
$loginPage->renderHead();
$loginPage->renderBody();
?>
