<?php
$db = null;
require_once('database.php');
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}


$product_name_q = filter_input(INPUT_GET, 'q');
$queryProducts = 'SELECT * FROM product_list WHERE product_name LIKE :product_name_q ORDER BY product_id';
$statement1 = $db->prepare($queryProducts);
$statement1->bindValue(':product_name_q', '%' . $product_name_q . '%');
$statement1->execute();
$product_list = $statement1->fetchAll();
$statement1->closeCursor();

$queryCategories = 'SELECT * FROM food_categories ORDER BY category_id';

$statement2 = $db->prepare($queryCategories);
$statement2->execute();
$product_categories = $statement2->fetchAll();
$statement2->closeCursor();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ecommerce</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <nav>
        <a href="http://localhost/GROUP3/">
            <h1>Product List</h1>
        </a>
        <?php if (isset($user_id)) {
            echo "<a href='connexion/disconnect_user.php'><h1>Disconnect</h1></a>";
            echo "<a href='commands/'><h1>Commands</h1></a>";
            echo "<a href='cart/'><h1>Cart</h1></a>";
        }
        else
        {
            echo "<a href='connexion/'><h1>Connect</h1></a>";
        }
        ?>
        </a>
    </nav>
    <header></header>
    <main>

        <nav>
            <form action="" method="GET">
                <input type="search" name="q" placeholder="Search a product...">
                <button type="submit">Search</button>
            </form>
        </nav>
        <div class="products">
            <?php
            if ($product_list == NULL)
                echo "Product Not Found";
            foreach ($product_list as $product) : ?>
                <div class="product">
                    <?php echo "<img src=\"images/".$product['product_id'].".png\" alt=\"".$product['product_name']." Image\">"; ?>
                    <div class="product_text">
                        <?php echo $product['product_name']; ?><br>
                        <?php echo $product['price']." yen"; ?>
                        <form id="myForm" action="cart/add_to_cart.php" method="post">
                            <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer></footer>
</body>
</html>
