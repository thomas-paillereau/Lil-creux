<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../');
    exit();
}
session_abort();

$user_name = filter_input(INPUT_POST, 'name');
$user_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$user_password = filter_input(INPUT_POST, 'password');
$sha1_password = sha1($user_password);

// Validate inputs
if ($user_name == null || $user_email == null || $user_password == null) {
    $error_message = "Invalid product data. Check all fields and try again.";
    include('../database_error.php');
} else {
    $db = 0;
    require_once('../database.php');

    $query = 'Select * from users where user_email = :email';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $user_email);
    $statement->execute();
    $email_exists = $statement->fetch();
    $statement->closeCursor();

    if ($email_exists) {
        $error_message = "Email already exists. Check all fields and try again.";
        include('../database_error.php');
    }
    else {
        // Add the product to the database
        $query = 'INSERT INTO users (user_name, user_email, password) VALUES (:name, :email, :password)';
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $user_name);
        $statement->bindValue(':email', $user_email);
        $statement->bindValue(':password', $sha1_password);
        $statement->execute();
        $statement->closeCursor();

        // Display the Product List page
        header('Location: ../');
        exit();
    }
}
?>