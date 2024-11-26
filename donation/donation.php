<!DOCTYPE html>
<html lang="en">
<head>
    <title>Donation Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
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

    <div class="container">
        <div class="left">
            <h1>Ready to make a change?</h1>
            <p>How to donate?</p>
            <p>Step 1: Type in the amount you want to donate</p>
            <p>Step 2: Press the Donate button to proceed to payment confirmation</p>
        </div>

        <div class="right">
            <h2>Choose an amount to donate</h2>
            <form action="donation_handler.php" method="post">
                <div class="amount">
                    <span>$</span>
                    <input type="text" name="donation_amount" class="text-amount" placeholder="0" min="1" step="0.01" required>
                </div>
                <button type="submit">Donate</button>
            </form>
        </div>
    </div>
</body>
</html>
