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

$queryTransactionRows = 'SELECT * FROM cart_rows WHERE user_id = :user_id ORDER BY cart_row_id';
$statement2 = $db->prepare($queryTransactionRows);
$statement2->bindValue(':user_id', $user_id);
$statement2->execute();
$cart_rows = $statement2->fetchAll();
$statement2->closeCursor();
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
    <div class="nav-bg"></div>
    <main>
        <div class="cart">
            <div class="listproducts">
                <?php if(!$cart_rows) {
                    echo "<p class='empty'>Nothing in your cart yet</p>";
                }
                ?>
                <?php
                    $price = 0;
                    foreach ($cart_rows as $cart_row) : 
                ?>
                <div class="cartRow">
                    <?php $product = $product_list[$cart_row['product_id']-1];?>
                    <?php $price += $cart_row['quantity'] * $product['price'];?>
                    <div class="cart-img">
                        <?php echo "<img src=\"../images/" . $product['product_id'] . ".png\" alt=\"" . $product['product_name'] . " Image\">";?>
                    </div>
                    <div class="cartdesc">
                        <p class="name">
                            <?php echo $product['product_name'];?>
                        </p>
                        <p class="price">
                            ¥<?php echo $product['price'];?>
                        </p>
                        <div class="cartactions">
                            <form id="myForm" action="remove_from_cart.php" method="post">
                                <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                                <button type="submit"><i class='bx  bx-trash'></i></button>
                            </form>
                            <div class="cartquantity">
                                <form id="myForm" action="-.php" method="post">
                                    <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                                    <button id='minusq' type="submit">-</button>
                                </form>
                                <p id="currquantity">
                                    <?php echo $cart_row['quantity'];?>
                                </p>
                                <form id="myForm" action="+.php" method="post">
                                    <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                                    <button id='plusq' type="submit">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="cartaddedprice">
                        <p class="price finalprice">¥<?php echo $product['price'] * $cart_row['quantity'];?></p>
                    </div>
                </div>
                <?php endforeach;?>
            </div> 
            <div class="finish">
                <?php if($cart_rows) {
                        echo "<div class='finalizeprice'><p id='total'>Total</p><p class='price'>¥".$price."</p></div>";
                        echo "<a href='checkout.php'>Order</a>";
                    }
                    else {
                        echo "
                        <div class='finalizeprice'>
                            <p id='total'>Total</p>
                            <p class='price'>¥0</p>
                        </div>
                        <p id='falsecheckout'>Order</p>
                        ";
                    }
                ?>
            </div>
        </div>
    </main>
</body>
</html>