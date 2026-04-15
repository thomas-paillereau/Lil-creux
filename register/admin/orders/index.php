<?php
$db = null;
require_once('../../database.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../connexion');
    exit();
}
else {
    $queryUsers = 'SELECT * FROM users WHERE admin = 1';
    $statement = $db->prepare($queryUsers);
    $statement->execute();
    $users = $statement->fetchAll();
    $statement->closeCursor();
    $is_admin = 0;
    foreach ($users as $user) {
        if ($user['user_id'] == $_SESSION['user_id']) {
            $user_id = $_SESSION['user_id'];
            $is_admin = 1;
            break;
        }
    }
    if ($is_admin == 0) {
        header('Location: ../../');
        exit();
    }
}
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Ecommerce</title>
        <link rel="stylesheet" href="../../style.css?v=3"/>
    </head>
    <body>
        <header class="header">
            <a href = "../" class="logo">
                <img src="../../images/logo.png"> 
                <h2><em>LIL'</em>CREUX<em>-ADMIN</em></h2>
            </a>
            <i class='bx bx-menu' id="menu-icon"></i>
            <nav class="navbar">
                <div class='user-menu nav-acc'>
                    <div class='dropdown'>
                        <?php echo "<a class='nav-acc pfp' href='#'>".$user['user_name'][0]."</a>";?>
                        <div class='dropdown-content'>
                            <a class='acc' href="../">Admin Page</a>
                            <a class='acc' href="../products/">Products</a>
                            <a class='acc' href="../users/">Users</a>
                            <a class='acc' href='../../'>Go Back</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <a href="../../">
                <h1>Main Page</h1>
            </a>
            <a href="../">
                <h1>Admin Main Page</h1>
            </a>
            <div class="cart">
                <div class=listproducts>
                    <?php
                        $queryProducts = 'SELECT * FROM product_list ORDER BY product_id';
                        $statement1 = $db->prepare($queryProducts);
                        $statement1->execute();
                        $product_list = $statement1->fetchAll();
                        $statement1->closeCursor();

                        $queryTransactions = 'SELECT * FROM transactions WHERE finish = 0 ORDER BY transaction_id';
                        $statement2 = $db->prepare($queryTransactions);
                        $statement2->execute();
                        $transactions = $statement2->fetchAll();
                        $statement2->closeCursor();

                        $queryTransactionRows = 'SELECT * FROM transaction_rows ORDER BY transaction_row_id';
                        $statement3 = $db->prepare($queryTransactionRows);
                        $statement3->execute();
                        $transactionRows = $statement3->fetchAll();
                        $statement3->closeCursor();

                        foreach ($transactions as $transaction) : 
                            $price = 0;?>
                            <?php echo '<div class="cartRow">';?>
                            <?php echo "<div class=cartdesc><h2>#" . $transaction['transaction_id'] . "</h2> <p class=name>" . $transaction['transaction_date'] . "</p>";?>
                                <form id="user" action="end_order.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $transaction['transaction_id']; ?>">
                                    <input type="hidden" name="finish" value="<?php echo $transaction['finish']; ?>">
                                    <button type="submit">Finish</button>
                                </form>
                                <?php
                                    foreach ($transactionRows as $transactionRow) {
                                        if ($transactionRow['transaction_id'] == $transaction['transaction_id']) {
                                            echo '<div class="cartRow">';
                                            $product = $product_list[$transactionRow['product_id']-1];
                                            $price += $transactionRow['quantity'] * $product['price'];
                                            echo "<div class=cart-img><img src=\"../../images/" . $product['product_id'] . ".png\" alt=\"" . $product['product_name'] . " Image\"></div>";
                                            echo "<div class=cartdesc><p class=name>" . $product['product_name'] . "</p> <p class=price>" . $product['price'] . "¥ x " . $transactionRow['quantity'] . "</p></div><div class=cartaddprice><p class=\"price finalprice\">" . ($product['price']*$transactionRow['quantity']) . "¥</p></div>"; 
                                            echo '</div>';
                                        }
                                    }
                                    echo "<h2>" . $price . "¥</h2>";
                                ?>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </main>
        <footer></footer>
    </body>
</html>