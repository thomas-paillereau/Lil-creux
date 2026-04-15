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

$user_name_q = filter_input(INPUT_POST, 'q');
$queryUsers = 'SELECT * FROM users WHERE user_name LIKE :user_name_q ORDER BY user_id';
$statement1 = $db->prepare($queryUsers);
$statement1->bindValue(':user_name_q', '%' . $user_name_q . '%');
$statement1->execute();
$users = $statement1->fetchAll();
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
                            <a class='acc' href="../products/">Products</a>
                            <a class='acc' href="../orders/">Orders</a>
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
                    <form id="nav-search" action="" method="POST">
                        <input type="search" name="q" placeholder="Search a user...">
                        <button type="submit">Search</button>
                    </form>
                    <?php
                        $price = 0;
                        foreach ($users as $user) : ?>
                            <?php echo '<div class="cartRow">';?>
                                <?php echo "<div class=cartdesc><h2>#" . $user['user_id'] . "</h2> <p class=name>" . $user['user_name'] . "</p><p class=price>" . $user['user_email'] . "</p>";?>
                                    <form id="user" action="perm.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">
                                        <input type="hidden" name="admin" value="<?php echo $user['admin']; ?>">
                                        <?php 
                                            if ($user['admin'] == 0) {
                                                echo "User";
                                            }
                                            else {
                                                echo "Admin";
                                            }
                                        ?>
                                        <button type="submit">Change</button>
                                    </form>
                                    <form id="user" action="remove_from_users.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">
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