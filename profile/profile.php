<?php
// Include database connection and start session
include '../php/php_connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Get the user ID from session
$email = $_SESSION['email']; // Get the email from session

// Fetch donation history from the database
$donation_history = [];
$sql = "SELECT donation_amount, donation_date FROM donations WHERE user_id = ? ORDER BY donation_date DESC";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $donation_history[] = $row;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body>
    <div class="icon"></div>
    <a href="../questions/questions.php">
    <div class="circle">
        <span class="icon-text">FFA</span>
    </div>
    </a>

    <div class="icon-right">
        <div class="home-icon">
            <a href="../home/home.php">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <a href="../profile/profile.php">
        <img src="../images/avatar.png" alt="Avatar">
        </a>
    </div>

    <div class="container" id="main-container">
        <img alt="Profile picture of <?php echo htmlspecialchars($email); ?>" class="profile-pic" height="100" src="../images/avatar.png" width="100"/>
        <div class="email" id="email-display"><?php echo htmlspecialchars($email); ?></div>
        <button class="edit-btn" onclick="showEditPopup()">Edit</button>
        <button class="logout-btn" onclick="window.location.href='../php/logout.php'">Logout</button>

        <div class="donation-history">
            <p>Donation History</p>
            <?php if (!empty($donation_history)): ?>
                <ul class="donation-list">
                    <?php foreach ($donation_history as $donation): ?>
                        <li>
                            <span class="donation-date"><?php echo date("F j, Y", strtotime($donation['donation_date'])); ?></span>
                            - <span class="donation-amount">$<?php echo number_format($donation['donation_amount'], 2); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No donations found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Form Pop-Up -->
    <div class="popup" id="edit-popup" style="display: none;">
        <div class="popup-content">
            <h2>Edit Profile</h2>
            <form action="update_profile.php" method="post">
                <div class="email">
                    <input type="email" name="email" placeholder="New Email Address" required>
                </div>
                <div class="email">
                    <input type="password" name="password" placeholder="New Password" required>
                </div>
                <div class="email">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="edit-btn">Save</button>
                <button type="button" class="logout-btn" onclick="hideEditPopup()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // Show the edit popup and blur background
        function showEditPopup() {
            document.getElementById('edit-popup').style.display = 'block';
            document.getElementById('main-container').classList.add('blurred');
        }

        // Hide the edit popup and remove blur from background
        function hideEditPopup() {
            document.getElementById('edit-popup').style.display = 'none';
            document.getElementById('main-container').classList.remove('blurred');
        }
    </script>
</body>
</html>
