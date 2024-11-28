<?php
session_start();
include '../php/php_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: ../login/login.php');
    exit;
}

$sql_comments = "SELECT * FROM comments";
$sql_donations = "SELECT * FROM donations";
$sql_users = "SELECT * FROM users";

$comments = $conn->query($sql_comments);
$donations = $conn->query($sql_donations);
$users = $conn->query($sql_users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-panel">
        <h1>Admin Dashboard</h1>
        
        <!-- Manage Comments -->
        <div class="section">
            <h2>Manage Comments</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Comment</th>
                        <th>Timestamp</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $comments->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['forum_comment']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                            <td>
                                <a href="edit_comment.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete.php?type=comment&id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Manage Donations -->
        <div class="section">
            <h2>Manage Donations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $donations->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['user_id']; ?></td>
                            <td><?php echo $row['donation_amount']; ?></td>
                            <td><?php echo $row['donation_date']; ?></td>
                            <td>
                                <a href="edit_donation.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete.php?type=donation&id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Manage Users -->
        <div class="section">
            <h2>Manage Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $users->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete.php?type=user&id=<?php echo $row['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
