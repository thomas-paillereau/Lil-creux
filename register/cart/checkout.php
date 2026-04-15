<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion');
    exit();
}
else
{
    $user_id = $_SESSION['user_id'];
}

session_abort();
$db = 0;
require_once('../database.php');

$query = 'SELECT * FROM cart_rows WHERE user_id = :user_id';
$statement1 = $db->prepare($query);
$statement1->bindValue(':user_id', $user_id);
$statement1->execute();
$cartRows = $statement1->fetchAll();
$statement1->closeCursor();

$query = 'INSERT INTO transactions (transaction_date, user_id) VALUES(NOW(), :user_id)';
$statement2 = $db->prepare($query);
$statement2->bindValue(':user_id', $user_id);
$statement2->execute();
$statement2->closeCursor();

$last_id = $db->lastInsertId();

foreach ($cartRows as $cartRow) {
    $query = 'INSERT INTO transaction_rows (transaction_id, product_id, quantity) VALUES(:transaction_id, :product_id, :quantity)';
    $statement3 = $db->prepare($query);
    $statement3->bindValue(':transaction_id', $last_id);
    $statement3->bindValue(':product_id', $cartRow['product_id']);
    $statement3->bindValue(':quantity', $cartRow['quantity']);
    $statement3->execute();
    $statement3->closeCursor();

    $query = 'DELETE FROM cart_rows WHERE cart_row_id = :cart_row_id';
    $statement4 = $db->prepare($query);
    $statement4->bindValue(':cart_row_id', $cartRow['cart_row_id']);
    $statement4->execute();
    $statement4->closeCursor();

    $query = 'UPDATE product_list SET n_bought = n_bought + :quantity WHERE product_id = :product_id';
    $statement5 = $db->prepare($query);
    $statement5->bindValue(':quantity', $cartRow['quantity']);
    $statement5->bindValue(':product_id', $cartRow['product_id']);
    $statement5->execute();
    $statement5->closeCursor();
}

header('Location: ../commands');
exit();
?>