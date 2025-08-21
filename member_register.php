<?php
include 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $membership_type = $_POST['membership_type'] ?? '';
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $status = 'active';

    // Basic validation - make sure all required fields are filled
    if ($full_name && $gender && $dob && $membership_type && $email && $password && $phone) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO members (full_name, gender, dob, membership_type, email, password, phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $full_name, $gender, $dob, $membership_type, $email, $hashed_password, $phone, $status);

        if ($stmt->execute()) {
            $message = "Registration successful! You can now login.";
        } else {
            // Could be duplicate email or other error
            $message = "Error: Could not register. Email might already exist.";
        }
        $stmt->close();
    } else {
        $message = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Member Registration</title>
  <style>
    body {
        background: #f4f7fa;
        font-family: Arial, sans-serif;
        margin: 0; padding: 0;
    }
    .container {
        max-width: 450px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #007bff;
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin: 10px 0 5px;
        font-weight: bold;
    }
    input, select {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    button {
        width: 100%;
        padding: 12px;
        background: #28a745;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        margin-top: 20px;
        cursor: pointer;
    }
    button:hover {
        background: #218838;
    }
    .message {
        text-align: center;
        color: red;
        font-weight: bold;
        margin-top: 15px;
    }
  </style>
</head>
<body>
<div class="container">
    <h2>Member Registration</h2>
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Full Name:</label>
        <input type="text" name="full_name" required />

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>

        <label>Date of Birth:</label>
        <input type="date" name="dob" required />

        <label>Membership Plan:</label>
        <select name="membership_type" required>
            <option value="">-- Select Plan --</option>
            <option value="monthly">Monthly - Rs.6000</option>
            <option value="yearly">Yearly - Rs.45000</option>
            <option value="quarterly">Quarterly - Rs.18000</option>
            <!-- Add more plans if needed -->
        </select>

        <label>Email:</label>
        <input type="email" name="email" required />

        <label>Phone Number:</label>
        <input type="text" name="phone" required />

        <label>Password:</label>
        <input type="password" name="password" required />

        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
