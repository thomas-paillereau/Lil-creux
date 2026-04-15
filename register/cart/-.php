<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion');
    exit();
}
else {
    $user_id = $_SESSION['user_id'];
}

$product_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

// Validate inputs
if ($product_id == NULL) {
    $error_message = "Invalid product data. Check all fields and try again.";
    include('../database_error.php');
} else {
    session_abort();
    $db = 0;
    require_once('../database.php');


    $query = 'Select * from cart_rows where user_id = :user_id AND product_id = :product_id ';
    $statement1 = $db->prepare($query);
    $statement1->bindValue(':user_id', $user_id);
    $statement1->bindValue(':product_id', $product_id);
    $statement1->execute();
    $product = $statement1->fetch();
    $statement1->closeCursor();

    if ($product) {
        $query = 'UPDATE cart_rows SET quantity = quantity - 1 WHERE product_id = :product_id AND user_id = :user_id';
        $statement2 = $db->prepare($query);
        $statement2->bindValue(':user_id', $user_id);
        $statement2->bindValue(':product_id', $product_id);
        $statement2->execute();
        $statement2->closeCursor();
    }

    $query = 'DELETE FROM cart_rows where user_id = :user_id AND quantity <= 0';
    $statement3 = $db->prepare($query);
    $statement3->bindValue(':user_id', $user_id);
    $statement3->execute();
    $statement3->closeCursor();

    header('Location: ./');
    exit();
}

?>