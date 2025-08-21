<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    echo "<script>alert('Trainer ID is missing.'); window.location.href='trainers.php';</script>";
    exit;
}

$trainer_id = $_GET['id'];

// Fetch current trainer data
$result = mysqli_query($conn, "SELECT * FROM trainers WHERE trainer_id = $trainer_id");
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Trainer not found.'); window.location.href='trainers.php';</script>";
    exit;
}

$trainer = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE trainers 
                    SET full_name='$full_name', gender='$gender', dob='$dob', phone='$phone', 
                        email='$email', specialization='$specialization', status='$status' 
                    WHERE trainer_id = $trainer_id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Trainer updated successfully.'); window.location.href='trainers.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Trainer</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 15px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Trainer</h2>
    <form method="POST">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($trainer['full_name']) ?>" required>

        <label for="gender">Gender</label>
        <select name="gender" required>
            <option value="Male" <?= $trainer['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $trainer['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= $trainer['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>

        <label for="dob">Date of Birth</label>
        <input type="date" name="dob" value="<?= $trainer['dob'] ?>" required>

        <label for="phone">Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($trainer['phone']) ?>" required>

        <label for="email">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($trainer['email']) ?>" required>

        <label for="specialization">Specialization</label>
        <input type="text" name="specialization" value="<?= htmlspecialchars($trainer['specialization']) ?>" required>

        <label for="status">Status</label>
        <select name="status" required>
            <option value="Active" <?= $trainer['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
            <option value="Inactive" <?= $trainer['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>

        <button type="submit">Update Trainer</button>
    </form>
    <a class="back-link" href="trainers.php">← Back to Trainer List</a>
</div>

</body>
</html>
