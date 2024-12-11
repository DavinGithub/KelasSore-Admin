<?php
include dirname(__FILE__) . '/../../../controllers/AuthController.php';

// Start session at the beginning
session_start();

if (isset($_SESSION['admin_id'])) {
    header('Location: ../pages/dashboard/dashboard.php');
    exit();
}   

// Instantiate AuthController
$authController = new AuthController();

// Handle login form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $remember_me = isset($_POST['remember_me']) ? true : false;

    $message = $authController->login($email, $password);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../../../assets/css/login/login.css">

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600&display=swap" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <!----------------------- Main Container -------------------------->
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <!----------------------- Login Container -------------------------->
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <!--------------------------- Left Box ----------------------------->
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #000842;">
                <div class="featured-image mb-8">
                    <img src="../../../assets/images/login.svg" class="img-fluid" style="width: 200px; height: 300px;" alt="Featured Image">
                </div>
                <p class="text-white fs-2" style="font-family: 'Manrope', sans-serif; font-weight: 600;">Sign in to KelasSore</p>
            </div>

            <!--------------------------- Right Box ---------------------------->
            <div class="col-md-5 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>KelasSore</h2>
                        <p>We are happy to have you back.</p>
                    </div>

                    <?php 
                    // Display login error message if exists
                    if (!empty($message)) {
                        echo "<div class='alert alert-danger'>" . htmlspecialchars($message) . "</div>";
                    }
                    ?>

                    <form action="login.php" method="POST">
                        <div class="input-group mb-3">
                            <input type="text" name="email" class="form-control form-control-lg bg-light fs-6" 
                                placeholder="Enter your email address" 
                                style="font-weight: 400;" 
                                required>
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" name="password" class="form-control form-control-lg bg-light fs-6" 
                                placeholder="Enter your password" 
                                style="font-weight: 400;" 
                                required>
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="formCheck" name="remember_me">
                                <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
                            </div>
                            <div class="forgot">
                                <small><a href="#">Forgot Password?</a></small>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Login</button>
                        </div>
                    </form>
                    <div class="input-group mb-3">
                        <button class="btn btn-lg btn-light w-100 fs-6">
                            <img src="../../../assets/images/google.svg" style="width:20px" class="me-2" alt="Google Logo">
                            <small>Sign In with Google</small>
                        </button>
                    </div>
                    <div class="row">
                        <small>Don't have an account? <a href="views/pages/register/register.php">Sign Up</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
