<?php
$db = null;
require_once('../database.php');
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

$selected = 0;
$product_name_q = filter_input(INPUT_GET, 'q');
$product_category_c = filter_input(INPUT_GET, 'c');
if (!isset($product_category_c) or $product_category_c == "0"){
    $queryProducts = 'SELECT * FROM product_list WHERE product_name LIKE :product_name_q ORDER BY product_id';
    $statement1 = $db->prepare($queryProducts);
    $statement1->bindValue(':product_name_q', '%' . $product_name_q . '%');
}
else {
    $selected = $product_category_c;
    $queryProducts = 'SELECT * FROM product_list WHERE product_name LIKE :product_name_q AND category_id = :category_id ORDER BY product_id';
    $statement1 = $db->prepare($queryProducts);
    $statement1->bindValue(':product_name_q', '%' . $product_name_q . '%');
    $statement1->bindValue(':category_id', $product_category_c);
}
$statement1->execute();
$product_list = $statement1->fetchAll();
$statement1->closeCursor();


$queryCategories = 'SELECT * FROM food_categories ORDER BY category_id';
$statement2 = $db->prepare($queryCategories);
$statement2->execute();
$product_categories = $statement2->fetchAll();
$statement2->closeCursor();

if (isset($user_id)) {
    $query = 'SELECT * FROM users WHERE user_id = :user_id';
    $statement3 = $db->prepare($query);
    $statement3->bindValue(':user_id', $user_id);
    $statement3->execute();
    $user = $statement3->fetch();
    $statement3->closeCursor();

    $queryCount = 'SELECT SUM(quantity) FROM cart_rows WHERE user_id = :user_id';
    $statementCount = $db->prepare($queryCount);
    $statementCount->bindValue(':user_id', $user_id);
    $statementCount->execute();
    $nb_cart = $statementCount->fetch();
    $statementCount->closeCursor();

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
}


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
<body class="">
    <header class="header">
        <a href = ".." class="logo">
            <img src="../images/logo.png">
            <h2><em>LIL'</em>CREUX</h2>
        </a>
        <i class='bx bx-menu' id="menu-icon"></i>
        <nav class="navbar">
            <a href="." id="nav-items">Items</a>
            <form id="nav-search" action="" method="GET">
                <?php
                if (!isset($product_name_q) or $product_name_q == "")
                    echo "<input type=\"search\" name=\"q\" placeholder=\"Search a product...\">";
                else
                    echo "<input type=\"search\" name=\"q\" placeholder=\"Search a product...\" value=\"".$product_name_q."\">";
                ?>
                <button type="submit"><i class='bx  bx-search'></i></button>
            </form>
            <?php 
                if (isset($user_id)) {
                    echo "<a id='cartcount' class='nav-acc restrainedx' href='../cart'><p>" .$nb_cart[0]."</p><i id='cartbtn' class='bx bx-cart'></i></a>";
                    echo "<div class='user-menu nav-acc'>";
                    echo "<div class='dropdown'>";
                    echo "<a class='nav-acc pfp' href='#'>".$user['user_name'][0]."</a>";
                    echo "<div class='dropdown-content'>";
                    echo "<a class='acc' href='../commands'>Orders</a>";
                    if($is_admin == 1) {
                        echo "<a class='acc' href='../admin'>Admin</a>";
                    }
                    echo "<a class='acc' href='../connexion/disconnect_user.php'>Disconnect</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                else
                {
                    echo "<a class='nav-acc' href='../connexion'>Log in</a>";
                }
            ?>
        </nav>
    </header>
    <div class="nav-bg"></div>
    <main>
        <h1 class="title">Find the perfect snack</h1>
        <div class="categories">
            <form action="./" method="GET">
                <select id="categories" name="c" >
                    <option value="0" <?php if (!$selected) echo "selected";?>>All</option>
                    <?php
                    foreach ($product_categories as $category) {
                        echo "<option value=" . $category['category_id'];
                        if ($selected == $category['category_id'])
                            echo " selected";
                        echo ">";
                        echo $category['category_name'];
                        echo "</option>";
                    }
                    ?>                <label for="Categories">Choose a category:</label>

                </select>
                <?php
                if (isset($product_name_q))
                    echo "<input type=\"hidden\" name=\"q\" value='$product_name_q'>";
                ?>
                <input id="refreshbtn" type="submit" value=">">
            </form>
        </div>
        <div class="products">
            <?php
            if ($product_list == NULL)
                echo "<h1 class='title spacefooter'>No product was found</h1>";
            foreach ($product_list as $product) : ?>
                <form id="myForm" action="../cart/add_to_cart.php" method="POST">
                    <input type="hidden" name="id" value=<?php echo $product['product_id']; ?>>
                    <?php if (isset($product_category_c)): ?>
                        <input type="hidden" name="c" value=<?php echo $product_category_c; ?>>
                    <?php endif ?>
                    <?php if (isset($product_name_q)): ?>
                        <input type="hidden" name="q" value=<?php echo $product_name_q; ?>>
                    <?php endif ?>
                    <button type="submit">
                        <div class="product">
                            <div class="product_img">
                                <span class="helper"></span>
                                <?php echo "<img src=\"../images/".$product['product_id'].".png\" alt=\"".$product['product_name']." Image\">"; ?>
                                <div class="addtocart" onclick="
                                                                document.getElementById('cartbtn').style.color='rgb(255,102,0)';
                                                                document.getElementById('cartbtn').style.transform='scale(1.1)';
                                                                document.getElementById('cartbtn').style.textShadown='0 0 20px rgb(255,102,0)';
                                                                "><i class='bx bx-cart-plus'></i></div>
                            </div>
                            <div class="product_text">
                                <p class="product_name"><?php echo $product['product_name']; ?></p>
                                <p class="product_price"><?php echo "¥".$product['price']; ?></p>
                            </div>
                        </div>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <div class="footlogo">
            <a href = ".." class="logo">
                <img src="../images/logo.png">
                <h2><em>LIL'</em>CREUX</h2>
            </a>
        </div>
        <div class="footlinks">
            <a href=".">Items</a>
            <a href="https://boxicons.com/">Icons from here</a>
            <a href="https://z125095-soler-thomas.github.io/midterm/">Team Leader : Thomas</a>
            <a href="https://z125093-paillereau-thomas.github.io/midterm/">Backend programmer : Thomas</a>
            <a href="https://z125025-dorian-bonis.github.io/midterm/">Frontend programmer : Dodie</a>
        </div>
        <div class="footinfo">
            <p class="info">This website was designed, programmed and released in the context of a school project. This is not a real e-commerce website and we won't charge you any price nor send you anything. This is purely a model.</p>
        </div>
    </footer>
</body>
</html>
