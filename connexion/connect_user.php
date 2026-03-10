<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ../');
    exit();
}

$user_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$user_password = filter_input(INPUT_POST, 'password');
$sha1_password = sha1($user_password);

// Validate inputs
if ($user_email == null || $user_password == null) {
    echo "Try Again";
    include ("index.php");
} else {
    $db = 0;
    require_once('../database.php');

    $query = 'Select * from users where user_email = :email AND password = :password';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $user_email);
    $statement->bindValue(':password', $sha1_password);
    $statement->execute();
    $user = $statement->fetch();
    $statement->closeCursor();

    if (!$user) {
        echo "Try Again";
        include ("index.php");
        echo $sha1_password . "<br>";
        echo $user_email . "<br>";
    }
    else {
        $user_id = $user['user_id'];
        $_SESSION['user_id'] = $user_id;
        header('Location: ../');
        exit();
    }
}
?>