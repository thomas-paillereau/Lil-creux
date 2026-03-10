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
    <title>Ecommerce</title>
    <link rel="stylesheet" href="cart.css"/>
</head>
<body>
<nav></nav>
<header></header>
<main>
    <a href="../">
        <h1>Product List</h1>
    </a>
    <div class="cart">
        <?php
            $price = 0;
            foreach ($cart_rows as $cart_row) : ?>
                <?php echo '<div class="cartRow">';?>
                <?php $product = $product_list[$cart_row['product_id']-1];?>
                <?php $price += $cart_row['quantity'] * $product['price'];?>
                <?php echo "<img src=\"../images/" . $product['product_id'] . ".png\" alt=\"" . $product['product_name'] . " Image\">";?>
                <?php echo "<div>" . $product['product_name'] . " :     " . $product['price'] . " yen x" . $cart_row['quantity'] . "</div>";?>
                <form id="myForm" action="+.php" method="post">
                    <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                    <button type="submit">+</button>
                </form>
                <form id="myForm" action="-.php" method="post">
                    <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                    <button type="submit">-</button>
                </form>
                <form id="myForm" action="remove_from_cart.php" method="post">
                    <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                    <button type="submit">Remove</button>
                </form>
                </div>
            <?php endforeach;
            if ($cart_rows) {
                echo '<h1>'.$price.' yen</h1>';
                echo '<a href="checkout.php">Checkout</a>';
            }
            else {
                echo '<h1>Rien RIEN</h1>';
            }
        ?>
    </div>
</main>
<footer></footer>
</body>
</html>