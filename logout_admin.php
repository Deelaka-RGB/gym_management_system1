<?php
session_start();

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging Out...</title>
    <meta http-equiv="refresh" content="3;url=admin_login.php"> <!-- Redirect after 3 seconds -->
    <style>
        body {
            background: #004aad;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .logout-message {
            background: #fff;
            color: #004aad;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .logout-message h2 {
            margin-bottom: 10px;
        }

        .logout-message p {
            font-size: 16px;
        }

        .loader {
            margin: 20px auto;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #004aad;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        // Prevent back navigation after logout
        window.history.pushState(null, "", window.location.href);
        window.onpopstate = function () {
            window.location.href = "admin_login.php";
        };
    </script>
</head>
<body>
    <div class="logout-message">
        <h2>Logging you out...</h2>
        <div class="loader"></div>
        <p>You will be redirected to the login page shortly.</p>
        <p>If not, <a href="admin_login.php" style="color: #004aad; font-weight: bold;">click here</a>.</p>
    </div>
</body>
</html>
