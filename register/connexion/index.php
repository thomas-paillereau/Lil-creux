<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- the head section -->
<head>
    <meta charset="UTF-8">
    <meta http-equip="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>LIL'CREUX</title>
    <link rel="stylesheet" href="../style.css"/>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="../script.js" defer></script>
</head>

<!-- the body section -->
<body class="sign">
    <header class="header sign">
        <a href = "../" class="logo">
            <img src="../images/logo.png"> 
            <h2><em>LIL'</em>CREUX</h2>
        </a>
    </header>

    <main class="sign">
        <div class="signbox log">
            <h2>Log In</h2>
            <form action="connect_user.php" method="post"
                id="connexion_form">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" required="required">
                    <label for="password">Password</label>
                    <input type="password" name="password" maxlength="40" required="required">
                </div>
                <input class="confirm" type="submit" value="Login">
            </form>
            <span class="line"></span>
            <a href="../register/">Register</a>
        </div>
    </main>
</body>
