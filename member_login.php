<?php
session_start();
include 'db_connect.php'; // Your database connection file

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $message = "Please enter both email and password.";
    } else {
        // Prepare statement to avoid SQL injection
        $stmt = $conn->prepare("SELECT member_id, full_name, password, status FROM members WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if ($user['status'] !== 'active') {
                $message = "Your account is not active. Please contact support.";
            } else {
                // Verify hashed password
                if (password_verify($password, $user['password'])) {
                    // Password matches, set session and redirect
                    $_SESSION['member_id'] = $user['member_id'];
                    $_SESSION['full_name'] = $user['full_name'];

                    // Redirect to member dashboard or homepage
                    header("Location: member_dashboard.php");
                    exit();
                } else {
                    $message = "Invalid email or password.";
                }
            }
        } else {
            $message = "Invalid email or password.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Member Login</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f7fa;
        margin: 0; padding: 0;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .login-container {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        width: 350px;
    }
    h2 {
        color: #007bff;
        margin-bottom: 25px;
        text-align: center;
    }
    label {
        font-weight: bold;
        display: block;
        margin-bottom: 6px;
    }
    input[type="email"], input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #ccc;
        margin-bottom: 20px;
        font-size: 16px;
    }
    button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        border: none;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #0056b3;
    }
    .message {
        margin-bottom: 15px;
        color: red;
        font-weight: bold;
        text-align: center;
    }
    .register-link {
        margin-top: 15px;
        text-align: center;
    }
    .register-link a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }
    .register-link a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="login-container">
    <h2>Member Login</h2>
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required autofocus />

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required />

        <button type="submit">Login</button>
    </form>

    <div class="register-link">
        <p>Don't have an account? <a href="member_register.php">Register here</a></p>
    </div>
</div>

</body>
</html>
