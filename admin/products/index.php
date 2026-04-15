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
        header('Location: ../..');
        exit();
    }
}

$product_name_q = filter_input(INPUT_POST, 'q');
$queryProducts = 'SELECT * FROM product_list WHERE product_name LIKE :product_name_q ORDER BY product_id';
$statement1 = $db->prepare($queryProducts);
$statement1->bindValue(':product_name_q', '%' . $product_name_q . '%');
$statement1->execute();
$product_list = $statement1->fetchAll();
$statement1->closeCursor();
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Ecommerce</title>
        <link rel="stylesheet" href="../../style.css"/>
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
                            <a class='acc' href="../users/">Users</a>
                            <a class='acc' href="../orders/">Orders</a>
                            <a class='acc' href='../../'>Go Back</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <div class="cart">
                <div class=listproducts>
                    <form id="nav-search" action="" method="POST">
                        <input type="search" name="q" placeholder="Search a product...">
                        <button type="submit">Search</button>
                    </form>
                    <div class=cartRow>
                        <div class="cartdesc">
                            <form action="add_to_stock.php" method="post" enctype="multipart/form-data">
                                <label for="imageUpload">Choose an image:</label>
                                <input type="file" id="imageUpload" name="imageUpload" accept="image/*" onchange="previewImage(event)"><br>

                                <label for="name">Name:</label>
                                <input type="text" id="name" name="name" required><br>

                                <label for="category">Category:</label>
                                <input type="text" id="category" name="category" required><br>

                                <label for="price">Price:</label>
                                <input type="number" id="price" name="price" required><br>

                                <label for="stock">Stock:</label>
                                <input type="number" id="stockInput" name="stock" required><br>

                                <input type="submit" value="Add">
                            </form>
                        </div>
                    </div>
                    <?php
                        foreach ($product_list as $product) : ?>
                            <?php echo '<div class="cartRow">';?>
                            <?php echo "<div class=cart-img><img src=\"../../images/" . $product['product_id'] . ".png\" alt=\"" . $product['product_name'] . " Image\"></div>";?>
                            <?php echo "<div class=cartdesc><div class=name>" . $product['product_name'] . " :</div>";?>
                            <form id="stock" action="stock.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                                <label for="price">Price:</label>
                                <input type="text" name="price" value="<?php echo $product['price']; ?>">
                                <?php echo "¥<br>";?>
                                <label for="stock">Stock:</label>
                                <input type="text" name="stock" value="<?php echo $product['stock']; ?>">
                                <?php echo "<br>";?>
                                <input type="submit" value=Save>
                            </form>
                            <form id="stock" action="remove_from_stock.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                                <button type="submit">Remove</button>
                            </form>
                            </div>
                            </div>
                    <?php endforeach;?>
                </div>
            </div>
        </main>
        <footer></footer>
    </body>
</html>