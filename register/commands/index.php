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

$query = 'SELECT * FROM users WHERE user_id = :user_id';
$statement3 = $db->prepare($query);
$statement3->bindValue(':user_id', $user_id);
$statement3->execute();
$user = $statement3->fetch();
$statement3->closeCursor();

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
    <meta charset="UTF-8">
    <meta http-equip="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>LIL'CREUX</title>
    <link rel="stylesheet" href="../style.css"/>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="../script.js" defer></script>
</head>
<body>
    <header class="header">
        <a href = "../" class="logo">
            <img src="../images/logo.png"> 
            <h2><em>LIL'</em>CREUX</h2>
        </a>
        <i class='bx bx-menu' id="menu-icon"></i>
        <nav class="navbar">
            <a href="../product_list/" id="nav-items" class="searchbar"><i class='bx  bx-search'></i></a>
            <div class='user-menu nav-acc'>
                <div class='dropdown'>
                    <?php echo "<a class='nav-acc pfp' href='#'>".$user['user_name'][0]."</a>";?>
                    <div class='dropdown-content'>
                        <a class='acc' href='../commands/'>Orders</a>
                        <a class='acc' href='../connexion/disconnect_user.php'>Disconnect</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <h1 class="title">Your orders</h1>
        <div class="commands">
            <?php
            foreach ($transactions as $transaction) {
                echo '<div class="transaction">';
                echo '<h2 class="datetransaction">' . $transaction['transaction_date'] . '</h2>';
                $price = 0;
                echo '<div class="ordered">';
                foreach ($transactionRows as $transactionRow) {
                    if ($transactionRow['transaction_id'] == $transaction['transaction_id']) {
                        echo '<div class="transactionRow">';
                        $product = $product_list[$transactionRow['product_id']-1];
                        $price += $transactionRow['quantity'] * $product['price'];
                        echo "<img src=\"../images/" . $product['product_id'] . ".png\" alt=\"" . $product['product_name'] . " Image\">";
                        echo "<h2>" . $product['product_name'] . " :     " . $product['price'] . " yen x" . $transactionRow['quantity'] . "</h2>";
                        echo '</div>';
                    }
                }
                echo '</div>';
                echo '<h1 class="fprice">Total:<br>¥' . $price . '</h1>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
</body>
</html>
