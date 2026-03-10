<?php
$db = null;
require_once('../database.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion');
    exit();
}
else {
    $user_id = $_SESSION['user_id'];
}

$queryProducts = 'SELECT * FROM product_list ORDER BY product_id';
$statement1 = $db->prepare($queryProducts);
$statement1->execute();
$product_list = $statement1->fetchAll();
$statement1->closeCursor();

$queryTransactions = 'SELECT * FROM transactions WHERE user_id = :user_id ORDER BY transaction_id DESC';
$statement4 = $db->prepare($queryTransactions);
$statement4->bindValue(':user_id', $user_id);
$statement4->execute();
$transactions = $statement4->fetchAll();
$statement4->closeCursor();

$queryTransactionRows = 'SELECT * FROM transaction_rows ORDER BY transaction_row_id';
$statement3 = $db->prepare($queryTransactionRows);
$statement3->execute();
$transactionRows = $statement3->fetchAll();
$statement3->closeCursor();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ecommerce</title>
    <link rel="stylesheet" href="commands.css"/>
</head>
<body>
    <nav></nav>
    <header></header>
    <main>
        <a href="../">
            <h1>Product List</h1>
        </a>
        <div class="commands">
            <?php
            foreach ($transactions as $transaction) {
                echo '<div class="transaction">';
                echo '<h2>' . $transaction['transaction_date'] . '</h2>';
                $price = 0;
                foreach ($transactionRows as $transactionRow) {
                    if ($transactionRow['transaction_id'] == $transaction['transaction_id']) {
                        echo '<div class="transactionRow">';
                        $product = $product_list[$transactionRow['product_id']-1];
                        $price += $transactionRow['quantity'] * $product['price'];
                        echo "<img src=\"../images/" . $product['product_id'] . ".png\" alt=\"" . $product['product_name'] . " Image\">";
                        echo "<div>" . $product['product_name'] . " :     " . $product['price'] . " yen x" . $transactionRow['quantity'] . "</div>";
                        echo '</div>';
                    }
                }
                echo '<h1>' . $price . ' yen</h1>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
    <footer></footer>
</body>
</html>
