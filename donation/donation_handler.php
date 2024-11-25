<?php
include '../php/php_connection.php';
include '../php/session_checker.php'; // Ensure the user is logged in

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donation_amount = $_POST['donation_amount'];

    if (!is_numeric($donation_amount) || $donation_amount <= 0) {
        echo "Invalid donation amount.";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $donation_date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO donations (user_id, donation_amount, donation_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ids", $user_id, $donation_amount, $donation_date);
        if ($stmt->execute()) {
            header("Location: ../home/home.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
}
?>
