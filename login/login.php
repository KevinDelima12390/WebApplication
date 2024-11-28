<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>

<?php
    include 'login_handler.php';
?>

<div class="container">
    <div class="left">
        <div class="login-box">
            <h2>Login</h2>
            
            <?php
                
                if (isset($_SESSION['error_message'])) {
                    echo "<div class='error-message'>" . $_SESSION['error_message'] . "</div>";
                   
                    unset($_SESSION['error_message']);
                }
            ?>

            <form action="login_handler.php" method="post">
                <input type="text" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button class="btn" type="submit">Login</button>
            </form>
        </div>
    </div>
    <div class="right">
        <h1>Welcome</h1>
        <h2>Back!</h2>
        <div class="text_box">
            <p>Don't have an <br> account?</p>
            <a href="../signup/signup.php">Signup!</a>
        </div>
    </div>
</div>

</body>
</html>
