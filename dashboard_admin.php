<?php
// Start session and protect access
session_start();

// Prevent browser from caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login if not logged in as admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "041130", "gym_management_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total members
$totalMembersResult = $conn->query("SELECT COUNT(*) as total FROM members");
$totalMembers = $totalMembersResult->fetch_assoc()['total'] ?? 0;

// Active trainers
$activeTrainersResult = $conn->query("SELECT COUNT(*) as total FROM trainers WHERE status = 'Active'");
$activeTrainers = $activeTrainersResult->fetch_assoc()['total'] ?? 0;

// Monthly revenue
$currentMonth = date('Y-m');
$monthlyRevenueResult = $conn->query("
    SELECT IFNULL(SUM(amount), 0) as total 
    FROM payments 
    WHERE status = 'paid' 
    AND DATE_FORMAT(payment_date, '%Y-%m') = '$currentMonth'
");
$monthlyRevenue = $monthlyRevenueResult ? ($monthlyRevenueResult->fetch_assoc()['total'] ?? 0) : 0;

// Total equipment
$equipmentResult = $conn->query("SELECT IFNULL(SUM(quantity), 0) as total FROM equipment");
$totalEquipment = $equipmentResult->fetch_assoc()['total'] ?? 0;

// Recent members
$recentMembersResult = $conn->query("SELECT member_id, full_name, email, dob, status FROM members ORDER BY dob DESC LIMIT 5");
$recentMembers = [];
if ($recentMembersResult && $recentMembersResult->num_rows > 0) {
    while ($row = $recentMembersResult->fetch_assoc()) {
        $recentMembers[] = $row;
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Gym Manager Dashboard</title>
<style>

    
   body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0; padding: 0;
    background: #f5f9ff;
    color: #0d2c54;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
 font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
 margin: 0; padding: 0;
 background: #f5f9ff;
 color: #0d2c54;
}
.dashboard-container {
 display: flex;
 min-height: 100vh;
 overflow: hidden;
}
.sidebar {
 width: 260px;
 background: #0a3d99;
 padding: 8px 25px;
 box-sizing: border-box;
 color: #dbe9ff;
 display: flex;
 flex-direction: column;
 box-shadow: 3px 0 15px rgba(10, 61, 153, 0.5);
}
.sidebar-header h2 {
 margin: 0 0 8px;
 font-weight: 999;
 font-size: 30px;
 letter-spacing: 1.8px;
 color: rgb(0, 0, 0);
 text-shadow: 1px 1px 4px rgb(9, 14, 21);
}
.sidebar-header p {
 color: #a3b8d1;
 font-size: 13px;
 margin: 0 0 25px;
 letter-spacing: 0.5px;
 font-style: italic;
}
.sidebar-menu {
 list-style: none;
 padding: 0;
 margin: 0;
 flex-grow: 1;
}
.sidebar-menu li {
 margin-bottom: 18px;
}
.sidebar-menu a {
 color: #bbe1ff;
 text-decoration: none;
 font-weight: 600;
 display: block;
 padding: 12px 16px;
 border-radius: 8px;
 cursor: pointer;
 box-shadow: inset 0 0 0 0 #bbe1ff;
 transition: all 0.25s ease;
 font-size: 16px;
}
.sidebar-menu a:hover,
.sidebar-menu a.active {
 background: #bbe1ff;
 color: #0a3d99;
 box-shadow: 0 4px 15px rgba(187, 225, 255, 0.7);
 font-weight: 700;
}
/* Main Content */
.main-content {
    flex-grow: 1;
    background: #ffffff;
    padding: 30px 40px;
    box-sizing: border-box;
    overflow-y: auto;
    color: #0d2c54;
    box-shadow: inset 0 0 20px #c9def8;
    display: flex;
    flex-direction: column;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 35px;
    border-bottom: 2px solid #bbe1ff;
    padding-bottom: 10px;
}

#pageTitle {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    color: #0a3d99;
    letter-spacing: 1px;
}

.user-info {
    color: #0d2c54;
    display: flex;
    align-items: center;
    gap: 14px;
    font-weight: 600;
    font-size: 16px;
    text-shadow: 0 1px 1px rgba(0,0,0,0.05);
}

.user-avatar {
    background: #0a3d99;
    color: #fff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 40px;
    font-weight: 900;
    font-size: 20px;
    box-shadow: 0 2px 8px rgba(10, 61, 153, 0.5);
    user-select: none;
}

/* Stats Grid */
.stats-grid {
    display: flex;
    gap: 24px;
    margin-bottom: 45px;
}

.stat-card {
    background: #e6f0ff;
    padding: 28px 0;
    border-radius: 14px;
    flex: 1;
    text-align: center;
    box-shadow: 0 8px 20px rgba(10, 61, 153, 0.15);
    color: #0a3d99;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: default;
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 40px rgba(10, 61, 153, 0.3);
}

.stat-icon {
    font-size: 48px;
    margin-bottom: 14px;
    filter: drop-shadow(0 0 1px rgba(10, 61, 153, 0.4));
}

.stat-number {
    font-size: 34px;
    font-weight: 800;
    margin-bottom: 6px;
    letter-spacing: 1.2px;
    user-select: text;
}

.stat-label {
    font-size: 15px;
    color: #004aad;
    letter-spacing: 0.8px;
    text-transform: uppercase;
}

/* Content Section */
.content-section {
    background: #e9f1ff;
    border-radius: 14px;
    padding: 28px 30px;
    box-shadow: 0 8px 24px rgba(10, 61, 153, 0.12);
    color: #0d2c54;
    flex-shrink: 0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.section-header h3 {
    margin: 0;
    color: #0a3d99;
    font-weight: 700;
    font-size: 22px;
    letter-spacing: 0.8px;
}

.btn {
    background: #0a3d99;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    font-weight: 700;
    font-size: 15px;
    text-decoration: none;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 12px rgba(10, 61, 153, 0.4);
}

.btn:hover {
    background: #004aad;
    box-shadow: 0 6px 18px rgba(0, 74, 173, 0.7);
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    color: #0d2c54;
    font-size: 15px;
    user-select: none;
}

th, td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1.5px solid #aac6ff;
}

th {
    background: #cce0ff;
    color: #003366;
    letter-spacing: 0.8px;
    font-weight: 700;
    text-transform: uppercase;
}

tbody tr:hover {
    background: #d3e0ff;
    cursor: pointer;
}

/* Status badges */
.status {
    padding: 6px 14px;
    border-radius: 18px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    user-select: none;
    display: inline-block;
    min-width: 80px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(10, 61, 153, 0.2);
}

.status.Active {
    background: #0a3d99;
    color: #fff;
    box-shadow: 0 0 12px #0a3d99;
}

.status.Pending {
    background: #f39c12;
    color: #fff;
    box-shadow: 0 0 12px #d38a0a;
}

/* Scrollbar for main content */
.main-content::-webkit-scrollbar {
    width: 10px;
}

.main-content::-webkit-scrollbar-track {
    background: #f5f9ff;
}

.main-content::-webkit-scrollbar-thumb {
    background-color: #0a3d99;
    border-radius: 6px;
    border: 2px solid #f5f9ff;
}

</style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>💪 GYM MANAGER</h2>
            <p>Admin Panel</p>
        </div>
        <ul class="sidebar-menu">
            <li><a href="#" class="active" onclick="showSection('dashboard', event)">📊 Dashboard</a></li>
            <li><a href="members.php">👥 Members</a></li>
            <li><a href="trainers.php">🏋️ Trainers</a></li>
            <li><a href="#" onclick="showSection('plans', event)">📋 Membership Plans</a></li>
            <li><a href="payment.php">💳 Payments</a></li>
            <li><a href="equipment.php">⚙️ Equipment</a></li>
            <li><a href="attendance.php">📅 Attendance</a></li>
            <li><a href="report_admin.php">📈 Reports</a></li>
            <li><a href="logout_admin.php">🚪 Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
   <div class="main-content">
    <div class="top-bar">
        <h1 id="pageTitle">Dashboard</h1>
        <div class="user-info">
            <span>Welcome, Admin</span>
            <a href="admin_profile.php" title="Go to Profile" style="text-decoration: none;">
                <div class="user-avatar" style="cursor: pointer;">A</div>
            </a>
        </div>
        </div>


        <!-- Dashboard Section -->
        <div id="dashboardSection">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number"><?php echo $totalMembers; ?></div>
                    <div class="stat-label">Total Members</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏋️</div>
                    <div class="stat-number"><?php echo $activeTrainers; ?></div>
                    <div class="stat-label">Active Trainers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number">Rs.<?php echo number_format($monthlyRevenue, 2); ?></div>
                    <div class="stat-label">Monthly Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⚙️</div>
                    <div class="stat-number"><?php echo $totalEquipment; ?></div>
                    <div class="stat-label">Equipment Items</div>
                </div>
            </div>

            <div class="content-section">
                <div class="section-header">
                    <h3>Recent Members</h3>
                    <a href="members.php" class="btn">View All</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date of Birth</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentMembers as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['member_id']); ?></td>
                                <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                <td><?php echo htmlspecialchars($member['dob']); ?></td>
                                <td><span class="status <?php echo htmlspecialchars($member['status']); ?>"><?php echo htmlspecialchars($member['status']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recentMembers)): ?>
                            <tr><td colspan="5" style="text-align:center;">No recent members found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Placeholder for other sections if needed -->

    </div>
</div>

<script>
function showSection(section, event) {
    event.preventDefault();
    alert("Section switching not implemented yet: " + section);
}

function logout() {
    if(confirm("Are you sure you want to logout?")) {
        alert("Logging out...");
        // Redirect to login page here, e.g.
        // window.location.href = "login.php";
    }
}
</script>
</body>
</html>
