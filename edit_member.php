<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $member_id = $_GET['id'];
    $query = "SELECT * FROM members WHERE member_id = $member_id";
    $result = mysqli_query($conn, $query);
    $member = mysqli_fetch_assoc($result);

    if (!$member) {
        die("Member not found.");
    }
}

if (isset($_POST['update'])) {
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $membership_type = $_POST['membership_type'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE members SET 
        full_name='$full_name',
        gender='$gender',
        dob='$dob',
        email='$email',
        phone='$phone',
        membership_type='$membership_type',
        status='$status'
        WHERE member_id=$member_id";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<script>alert('Member updated successfully!'); window.location.href='your_dashboard_page.php';</script>";
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .form-container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; margin-bottom: 15px; }
        .btn { padding: 10px 20px; background: #28a745; border: none; color: white; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Member</h2>
        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($member['full_name']) ?>" required>

            <label>Gender</label>
            <select name="gender" required>
                <option value="Male" <?= $member['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $member['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $member['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>

            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= $member['dob'] ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($member['email']) ?>" required>

            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($member['phone']) ?>" required>

            <label>Membership Type</label>
            <input type="text" name="membership_type" value="<?= htmlspecialchars($member['membership_type']) ?>" required>

            <label>Status</label>
            <select name="status" required>
                <option value="Active" <?= $member['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $member['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>

            <button type="submit" name="update" class="btn">Update Member</button>
        </form>
    </div>
</body>
</html>
