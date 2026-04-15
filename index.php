<?php
$db = null;
require_once('database.php');
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

$queryProducts = 'SELECT * FROM product_list WHERE stock != 0 ORDER BY n_bought';
$statement1 = $db->prepare($queryProducts);
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
    <link rel="stylesheet" href="style.css"/>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <script src="script.js" defer></script>
</head>
<body>
    <header class="header">
        <a href = "." class="logo">
            <img src="images/logo.png">
            <h2><em>LIL'</em>CREUX</h2>
        </a>
        <i class='bx bx-menu' id="menu-icon"></i>
        <nav class="navbar">
            <a href="product_list/" id="nav-items">Items</a>
            <form id="nav-search" action="./product_list" method="GET">
                <input type="search" name="q" placeholder="Search a product...">
                <button type="submit"><i class='bx  bx-search'></i></button>
            </form>
            <?php 
                if (isset($user_id)) {
                    echo "<a id='cartcount' class='nav-acc restrainedx' href='cart/'><p>" .$nb_cart[0]."</p><i id='cartbtn' class='bx bx-cart'></i></a>";
                    echo "<div class='user-menu nav-acc'>";
                    echo "<div class='dropdown'>";
                    echo "<a class='nav-acc pfp' href='#'>".$user['user_name'][0]."</a>";
                    echo "<div class='dropdown-content'>";
                    echo "<a class='acc' href='commands'>Orders</a>";
                    if($is_admin == 1) {
                        echo "<a class='acc' href='admin'>Admin</a>";
                    }
                    echo "<a class='acc' href='connexion/disconnect_user.php'>Disconnect</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                else
                {
                    echo "<a class='nav-acc' href='connexion'>Log in</a>";
                }
            ?>
        </nav>
    </header>
    <div class="nav-bg"></div>
    <main class="home">
        <div class="categ">
            <?php 
            $even = 0;
            foreach ($product_categories as $product_category) : ?>
                <div class="item">
                    <form class="slideprod" action="./product_list" method="GET">
                        <input type="hidden" id="categories" value=<?php echo $product_category['category_id']; ?> name = "c">
                        <button type="submit">
                            <?php
                            $tmp = "s";
                            if ($product_category['category_name'][-1] == 's') {
                                $tmp = "";
                            }
                            echo "<div draggable='false' class='recoprod slide".($even%2)."'".$product_category['category_name'].$tmp."\">";
                            echo "<h1 class='recotitle'><em>Our</em> ".$product_category['category_name'].$tmp."</h1>";
                            $queryCategories = 'SELECT * FROM product_list WHERE category_id = :category_id';
                            $statement2 = $db->prepare($queryCategories);
                            $statement2->bindValue(':category_id', $product_category['category_id']);
                            $statement2->execute();
                            $items = $statement2->fetchAll();
                            $statement2->closeCursor();
                            $even = $even + 1;
                            $item = $items[array_rand($items)];
                            echo "<img class='recoimg' src=\"images/".$item['product_id'].".png\" alt=\"drink image\">";
                            ?>
                            </div>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <h1 id="bestseller" class="title">Best Sellers</h1>
        <div class="bestSeller">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <?php $name = $product_list[$i]['product_name'];
                for ($j = 0; $j < strlen($name); $j++) {
                    if ($name[$j] == ' ')
                        $name[$j] = '_';
                }
                ?>
                <form id="myForm" action="product_list/" method="GET">
                    <input type="hidden" name="q" value=<?php echo $name; ?>>
                    <button type="submit">
                        <div class='prodseller'>
                            <img src="images/<?php echo $product_list[$i]['product_id']?>.png">
                        </div>
                    </button>
                </form>
            <?php endfor ?>
        </div>
    </main>
    <footer>
        <div class="footlogo">
            <a href = "." class="logo">
                <img src="images/logo.png">
                <h2><em>LIL'</em>CREUX</h2>
            </a>
        </div>
        <div class="footlinks">
            <a href="product_list/">Items</a>
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
