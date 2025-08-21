<?php
include 'db_connect.php';

// Query sessions with trainer names
$sql = "SELECT s.session_id, s.session_name, s.session_date, s.start_time, s.end_time, s.max_participants,
               t.full_name AS trainer_name
        FROM sessions s
        LEFT JOIN trainers t ON s.trainer_id = t.trainer_id
        ORDER BY s.session_date, s.start_time";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Sessions List</title>
<style>
    /* Reset */
    *, *::before, *::after {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #71b7e6, #9b59b6);
        margin: 0; 
        padding: 0;
        min-height: 100vh;
        color: #333;
    }

    .container {
        max-width: 1100px;
        margin: 60px auto 40px;
        background: #fff;
        padding: 30px 40px;
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        position: relative;
    }

    h1 {
        text-align: center;
        font-weight: 700;
        font-size: 2.8rem;
        color: #5a2d82;
        margin-bottom: 30px;
        letter-spacing: 1px;
    }

    /* Home button */
    .home-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        background: #5a2d82;
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 6px 15px rgba(90, 45, 130, 0.5);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        z-index: 9999;
    }
    .home-btn:hover {
        background: #7b47b6;
        box-shadow: 0 8px 20px rgba(123, 71, 182, 0.7);
    }

    /* Add session button */
    .add-btn {
        background-color: #27ae60;
        color: white;
        padding: 14px 26px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        margin-bottom: 25px;
        display: inline-block;
        box-shadow: 0 6px 18px rgba(39, 174, 96, 0.45);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        user-select: none;
    }
    .add-btn:hover {
        background-color: #219150;
        box-shadow: 0 8px 25px rgba(33, 145, 80, 0.6);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
        font-size: 1rem;
        min-width: 700px;
        table-layout: fixed;
    }

    thead tr {
        background: #5a2d82;
        color: white;
        text-align: left;
        font-weight: 600;
        letter-spacing: 0.05em;
        border-radius: 12px;
        user-select: none;
    }

    thead th {
        padding: 15px 20px;
        vertical-align: middle;
    }

    tbody tr {
        background: #fafafa;
        box-shadow: 0 4px 8px rgb(0 0 0 / 0.05);
        border-radius: 12px;
        transition: transform 0.15s ease;
        cursor: default;
    }
    tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgb(90 45 130 / 0.15);
    }

    tbody td {
        padding: 15px 20px;
        vertical-align: middle;
        overflow-wrap: break-word;
    }

    /* Buttons inside table */
    a.button {
        padding: 8px 14px;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        margin-right: 10px;
        display: inline-block;
        user-select: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: background-color 0.25s ease, box-shadow 0.25s ease;
    }
    a.edit-btn {
        background-color: #2980b9;
    }
    a.edit-btn:hover {
        background-color: #1f6391;
        box-shadow: 0 6px 18px rgba(41, 128, 185, 0.6);
    }
    a.delete-btn {
        background-color: #c0392b;
    }
    a.delete-btn:hover {
        background-color: #89231a;
        box-shadow: 0 6px 18px rgba(192, 57, 43, 0.6);
    }

    /* Responsive */
    @media (max-width: 900px) {
        .container {
            margin: 80px 15px 40px;
            padding: 25px 20px;
            overflow-x: auto;
        }

        table {
            min-width: 600px;
        }
    }

    @media (max-width: 600px) {
        table, thead, tbody, th, td, tr {
            display: block;
        }
        thead tr {
            display: none;
        }
        tbody tr {
            background: white;
            margin-bottom: 25px;
            padding: 20px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        }
        tbody td {
            padding-left: 50%;
            position: relative;
            border-bottom: 1px solid #eee;
        }
        tbody td::before {
            content: attr(data-label);
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 700;
            color: #555;
            white-space: nowrap;
        }
        a.button {
            margin: 8px 5px 0 0;
        }
    }
</style>
</head>
<body>

<a href="member_dashboard.php" class="home-btn" title="Home">🏠 Home</a>

<div class="container">
    <h1>Sessions List</h1>
    <a href="add_session.php" class="add-btn">➕ Add New Session</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Session Name</th>
                <th>Trainer</th>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Max Participants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td data-label="ID"><?php echo htmlspecialchars($row['session_id']); ?></td>
                    <td data-label="Session Name"><?php echo htmlspecialchars($row['session_name']); ?></td>
                    <td data-label="Trainer"><?php echo htmlspecialchars($row['trainer_name'] ?? 'Unassigned'); ?></td>
                    <td data-label="Date"><?php echo htmlspecialchars($row['session_date']); ?></td>
                    <td data-label="Start Time"><?php echo htmlspecialchars($row['start_time']); ?></td>
                    <td data-label="End Time"><?php echo htmlspecialchars($row['end_time']); ?></td>
                    <td data-label="Max Participants"><?php echo htmlspecialchars($row['max_participants']); ?></td>
                    <td data-label="Actions">
                        <a href="edit_session.php?id=<?php echo $row['session_id']; ?>" class="button edit-btn">Edit</a>
                        <a href="delete_session.php?id=<?php echo $row['session_id']; ?>" class="button delete-btn" onclick="return confirm('Are you sure you want to delete this session?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center; padding:30px 0;">No sessions found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
