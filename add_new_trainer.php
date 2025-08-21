<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $status = $_POST['status'];

    $sql = "INSERT INTO trainers (full_name, gender, dob, phone, email, specialization, status)
            VALUES ('$full_name', '$gender', '$dob', '$phone', '$email', '$specialization', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Trainer added successfully!'); window.location.href='trainers.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Trainer</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f1f2f6, #dfe4ea);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px;
            min-height: 100vh;
        }
        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2f3542;
        }
        label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 12px 14px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background: #f9f9f9;
        }
        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
            background: #fff;
        }
        button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            background: #28a745;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #218838;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>➕ Add New Trainer</h2>
    <form method="POST">
        <label for="full_name">Full Name</label>
        <input type="text" name="full_name" required>

        <label for="gender">Gender</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="dob">Date of Birth</label>
        <input type="date" name="dob" required>

        <label for="phone">Phone Number</label>
        <input type="text" name="phone" required>

        <label for="email">Email Address</label>
        <input type="email" name="email" required>

        <label for="specialization">Specialization</label>
        <input type="text" name="specialization" required>

        <label for="status">Status</label>
        <select name="status" required>
            <option value="">-- Select Status --</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>

        <button type="submit">Add Trainer</button>
    </form>
    <a class="back-link" href="trainers.php">← Back to Trainer List</a>
</div>

</body>
</html>
