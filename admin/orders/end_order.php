<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../');
    exit();
}
else {
    $user_id = $_SESSION['user_id'];
}

$transaction_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$finish = filter_input(INPUT_POST, 'finish', FILTER_SANITIZE_NUMBER_INT);

// Validate inputs
if ($transaction_id == NULL) {
    $error_message = "Invalid user data. Check all fields and try again.";
    include('../../database_error.php');
} else {
    session_abort();
    $db = 0;
    require_once('../../database.php');

    if ($finish == 0){
        $finish = 1;
    }
    else{
        $finish = 0;
    }

    $query = 'UPDATE transactions SET finish = :finish WHERE transaction_id = :transaction_id';
    $statement3 = $db->prepare($query);
    $statement3->bindValue(':transaction_id', $transaction_id);
    $statement3->bindValue(':finish', $finish);
    $statement3->execute();
    $statement3->closeCursor();

    header('Location: ./');
    exit();
}

?>