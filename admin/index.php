<?php
$db = null;
require_once('../database.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: ../connexion');
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
        header('Location: ..');
        exit();
    }
}

$queryProducts = 'SELECT * FROM product_list ORDER BY product_id';
$statement1 = $db->prepare($queryProducts);
$statement1->execute();
$product_list = $statement1->fetchAll();
$statement1->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ecommerce</title>
    <link rel="stylesheet" href="admin.css"/>
</head>
<body>
<nav></nav>
<header></header>
<main>
    <a href="../">
        <h1>Main Page</h1>
    </a>
    <a href="users/">
        <h1>Users</h1>
    </a>
    <a href="orders/">
        <h1>Orders</h1>
    </a>
    <div class="admin">
        <?php
            $price = 0;
            foreach ($product_list as $product) : ?>
                <?php echo '<div class="row">';?>
                <?php echo "<img src=\"../images/" . $product['product_id'] . ".png\" alt=\"" . $product['product_name'] . " Image\">";?>
                <?php echo "<div>" . $product['product_name'] . " :</div>";?>
                <form id="stock" action="stock.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                    <input type="text" name="price" value="<?php echo $product['price']; ?>">
                    <?php echo "yen x";?>
                    <input type="text" name="stock" value="<?php echo $product['stock']; ?>">
                    <input type="submit" value=Save>
                </form>
                <form id="stock" action="remove_from_stock.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                    <button type="submit">Remove</button>
                </form>
                </div>
            <?php endforeach;?>
    </div>
</main>
<footer></footer>
</body>
</html>