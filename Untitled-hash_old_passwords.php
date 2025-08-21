<?php
include 'db_connect.php';

// Fetch all members
$sql = "SELECT member_id, password FROM members";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$updated = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $member_id = $row['member_id'];
    $password = $row['password'];

    // Skip if already hashed
    if (strpos($password, '$2y$') === 0) {
        continue;
    }

    // Hash the old plain-text password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update the database
    $update_sql = "UPDATE members SET password = ? WHERE member_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $hashed_password, $member_id);

    if ($stmt->execute()) {
        $updated++;
    }
}

echo "Password update complete. Hashed $updated passwords.";
?>
