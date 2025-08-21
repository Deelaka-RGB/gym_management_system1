<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $trainer_id = $_GET['id'];

    // First, check if the trainer exists
    $checkQuery = "SELECT * FROM trainers WHERE trainer_id = $trainer_id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Try deleting the trainer
        $deleteQuery = "DELETE FROM trainers WHERE trainer_id = $trainer_id";

        if (mysqli_query($conn, $deleteQuery)) {
            echo "<script>alert('Trainer deleted successfully.'); window.location.href='trainers.php';</script>";
        } else {
            echo "<script>alert('Error deleting trainer: " . mysqli_error($conn) . "'); window.location.href='trainers.php';</script>";
        }
    } else {
        echo "<script>alert('Trainer not found.'); window.location.href='trainers.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='trainers.php';</script>";
}

mysqli_close($conn);
?>
