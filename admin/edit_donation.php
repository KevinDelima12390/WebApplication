<?php
session_start();
include '../php/php_connection.php';

// Check if the user is an admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login/login.php");
    exit;
}

// Check if the donation ID is passed in the URL
if (isset($_GET['id'])) {
    $donation_id = $_GET['id'];

    // Fetch the donation record from the database
    $sql = "SELECT * FROM donations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $donation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $donation = $result->fetch_assoc();
    } else {
        $_SESSION['error_message'] = "Donation record not found.";
        header("Location: admin_dashboard.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Donation ID is missing.";
    header("Location: admin_dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $donation_amount = trim($_POST['donation_amount']);
    $donation_date = trim($_POST['donation_date']);

    if (empty($donation_amount) || !is_numeric($donation_amount) || $donation_amount <= 0) {
        $_SESSION['error_message'] = "Please enter a valid donation amount.";
    } elseif (empty($donation_date)) {
        $_SESSION['error_message'] = "Please enter a valid donation date.";
    } else {
        // Update the donation record in the database
        $sql = "UPDATE donations SET donation_amount = ?, donation_date = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsi", $donation_amount, $donation_date, $donation_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Donation record updated successfully.";
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Error updating donation record: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Donation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="admin-panel">
        <h1>Edit Donation</h1>

        <?php
        // Display error or success messages
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>" . $_SESSION['success_message'] . "</p>";
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="edit_donation.php?id=<?php echo $donation['id']; ?>" method="post">
            <label for="donation_amount">Donation Amount:</label>
            <input type="number" name="donation_amount" value="<?php echo htmlspecialchars($donation['donation_amount']); ?>" required><br><br>

            <label for="donation_date">Donation Date:</label>
            <input type="date" name="donation_date" value="<?php echo htmlspecialchars($donation['donation_date']); ?>" required><br><br>

            <button type="submit">Update Donation</button>
        </form>
        
        <br>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
