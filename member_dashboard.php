<?php
session_start();

// Protect page - ensure member is logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id'];

// Connect to DB
$conn = new mysqli("localhost", "root", "", "gym_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch member details
$stmt = $conn->prepare("SELECT full_name, membership_type, status FROM members WHERE member_id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$stmt->bind_result($full_name, $membership_type, $status);
$stmt->fetch();
$stmt->close();

// Dummy stats - replace with your real queries as needed
$personal_trainings = 3;
$gym_sessions = 12;
$attendance_percent = 85;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Member Dashboard</title>
<style>
    /* Reset */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #e0f0ff, #f5f9ff);
        color: #0d2c54;
        min-height: 100px;
        display: flex;
        justify-content: center;
        align-items: stretch;
    }

    .dashboard-container {
        display: flex;
        width: 150%;
        max-width: 1600px;
        height: 100vh;
        box-shadow: 0 0 30px rgba(10, 61, 153, 0.15);
        border-radius: 2px;
        overflow: hidden;
        background: #fff;
    }

    /* Sidebar */
    .sidebar {
        width: 280px;
        background: linear-gradient(180deg, #0a3d99 0%, #064288 100%);
        padding: 30px 30px 40px 30px;
        color: #dbe9ff;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 30px rgba(10, 61, 153, 0.4);
        transition: width 0.4s ease;
    }

    .sidebar-header h2 {
        font-weight: 900;
        font-size: 34px;
        letter-spacing: 2px;
        margin-bottom: 40px;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
        user-select: none;
    }

    .sidebar-menu {
        list-style: none;
        flex-grow: 1;
    }

    .sidebar-menu li {
        margin-bottom: 20px;
    }

    .sidebar-menu a {
        color: #bbe1ff;
        text-decoration: none;
        font-weight: 600;
        display: block;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 17px;
        box-shadow: inset 0 0 0 0 #bbe1ff;
        transition: all 0.3s ease;
        user-select: none;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background: #bbe1ff;
        color: #0a3d99;
        box-shadow: 0 6px 20px rgba(187, 225, 255, 0.7);
        font-weight: 700;
        transform: translateX(5px);
    }

    /* Main Content */
    .main-content {
        flex-grow: 1;
        padding: 40px 60px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        background: #fefefe;
        box-shadow: inset 0 0 20px #c9def8;
        border-radius: 0 16px 16px 0;
        color: #0d2c54;
    }

    /* Top bar */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #bbe1ff;
        padding-bottom: 15px;
        margin-bottom: 40px;
    }

    #pageTitle {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: 1.2px;
        color: #0a3d99;
        user-select: none;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 600;
        font-size: 18px;
        color: #0d2c54;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        user-select: none;
    }

    /* Profile Avatar Circle */
    .user-avatar {
        background: #0a3d99;
        color: #fff;
        border-radius: 50%;
        width: 44px;
        height: 44px;
        text-align: center;
        line-height: 44px;
        font-weight: 900;
        font-size: 22px;
        box-shadow: 0 3px 10px rgba(10, 61, 153, 0.6);
        user-select: none;
        flex-shrink: 0;
    }

    /* Profile button next to welcome */
    .profile-button {
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 28px;
        color: #0a3d99;
        padding: 6px;
        border-radius: 50%;
        transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
        box-shadow: 0 0 6px rgba(10, 61, 153, 0.3);
    }

    .profile-button:hover {
        background-color: #0a3d99;
        color: #bbe1ff;
        transform: scale(1.15);
        box-shadow: 0 0 14px #0a3d99;
    }

    .profile-button:focus {
        outline: none;
        box-shadow: 0 0 16px 3px #0a3d99;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(180px,1fr));
        gap: 28px;
    }

    .stat-card {
        background: linear-gradient(145deg, #e6f0ff, #d3e1ff);
        padding: 28px 0;
        border-radius: 18px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(10, 61, 153, 0.18);
        color: #0a3d99;
        transition: transform 0.4s ease, box-shadow 0.4s ease;
        cursor: default;
        user-select: none;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 22px 50px rgba(10, 61, 153, 0.35);
    }

    .stat-icon {
        font-size: 52px;
        margin-bottom: 18px;
        filter: drop-shadow(0 0 2px rgba(10, 61, 153, 0.4));
    }

    .stat-number {
        font-size: 38px;
        font-weight: 900;
        margin-bottom: 8px;
        letter-spacing: 1.3px;
        user-select: text;
    }

    .stat-label {
        font-size: 16px;
        color: #004aad;
        letter-spacing: 1.1px;
        text-transform: uppercase;
    }

    /* Scrollbar styling for main content */
    .main-content::-webkit-scrollbar {
        width: 8px;
    }

    .main-content::-webkit-scrollbar-thumb {
        background: #0a3d99;
        border-radius: 10px;
    }

    .main-content::-webkit-scrollbar-track {
        background: #e0f0ff;
        border-radius: 10px;
    }
</style>
</head>
<body>
<div class="dashboard-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>💪 High Impact Fitness</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active">📊 Dashboard</a></li>
            <li><a href="session.php">🏋️ Sessions</a></li>
            <li><a href="personal_training.php">🤸 Personal Trainings</a></li>
            <li><a href="member_attendance.php">📅 Attendance Reports</a></li>
            <li><a href="payments.php">💳 Payment</a></li>
            <li><a href="feedback.php">📝 Feedback</a></li>
            <li><a href="logout_member.php">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <h1 id="pageTitle">Dashboard</h1>
            <div class="user-info" title="<?php echo htmlspecialchars($full_name); ?>">
                <div class="user-avatar" aria-label="User Initial">
                    <?php echo strtoupper(substr($full_name, 0, 1)); ?>
                </div>
                <span>Welcome, <?php echo htmlspecialchars($full_name); ?></span>
                <button class="profile-button" title="View Profile" aria-label="View Profile" onclick="location.href='profile.php'">👤</button>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <div class="stat-number"><?php echo htmlspecialchars($membership_type); ?></div>
                <div class="stat-label">Membership Plan</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🤸</div>
                <div class="stat-number"><?php echo $personal_trainings; ?></div>
                <div class="stat-label">Personal Trainings</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏋️</div>
                <div class="stat-number"><?php echo $gym_sessions; ?></div>
                <div class="stat-label">Gym Sessions</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-number"><?php echo $attendance_percent; ?>%</div>
                <div class="stat-label">Attendance</div>
            </div>
        </div>

    </div>

</div>
</body>
</html>
