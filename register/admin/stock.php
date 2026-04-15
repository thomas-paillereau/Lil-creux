<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ..');
    exit();
}
else {
    $user_id = $_SESSION['user_id'];
}

$product_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT);
$stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);

// Validate inputs
if ($product_id == NULL) {
    $error_message = "Invalid product data. Check all fields and try again.";
    include('../database_error.php');
} else {
    require_once('../database.php');

    $query = 'UPDATE product_list SET price = :price, stock = :stock WHERE product_id = :product_id';
    $statement3 = $db->prepare($query);
    $statement3->bindValue(':product_id', $product_id);
    $statement3->bindValue(':price', $price);
    $statement3->bindValue(':stock', $stock);
    $statement3->execute();
    $statement3->closeCursor();
    header('Location: ./');
}
?>