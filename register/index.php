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
    <title>Ecommerce</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<!-- the body section -->
<body>
<header><h1>User Registration</h1></header>

<main>
    <form action="add_user.php" method="post"
          id="add_product_form">
        <label>Name:</label>
        <input type="text" name="name"><br>
        <label>Email:</label>
        <input type="text" name="email"><br>
        <label>Password:</label>
        <input type="text" name="password"><br>
        <label>&nbsp;</label>
        <input type="submit" value="Register"><br>
    </form>
    <p><a href="../connexion/">Connexion</a></p>
    <p><a href="../">View Product List</a></p>
</main>
</body>
</html>