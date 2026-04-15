<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../');
    exit();
}
else {
    $user_id = $_SESSION['user_id'];
}

$user_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

// Validate inputs
if ($product_id == NULL) {
    $error_message = "Invalid user data. Check all fields and try again.";
    include('../../database_error.php');
} else {
    session_abort();
    $db = 0;
    require_once('../../database.php');

    $query = 'DELETE FROM users where user_id = :user_id';
    $statement3 = $db->prepare($query);
    $statement3->bindValue(':user_id', $user_id);
    $statement3->execute();
    $statement3->closeCursor();

    header('Location: ./');
    exit();
}

?>