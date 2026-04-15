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
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Ecommerce</title>
        <link rel="stylesheet" href="../style.css"/>
    </head>
    <body>
        <header class="header">
            <a href = "./" class="logo">
                <img src="../images/logo.png"> 
                <h2><em>LIL'</em>CREUX<em>-ADMIN</em></h2>
            </a>
            <i class='bx bx-menu' id="menu-icon"></i>
            <nav class="navbar">
                <div class='user-menu nav-acc'>
                    <div class='dropdown'>
                        <?php echo "<a class='nav-acc pfp' href='#'>".$user['user_name'][0]."</a>";?>
                        <div class='dropdown-content'>
                            <a class='acc' href="products/">Products</a>
                            <a class='acc' href="users/">Users</a>
                            <a class='acc' href="orders/">Orders</a>
                            <a class='acc' href='../'>Go Back</a>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <div>
                <h1 class="title">Administrator's Board</h1>
                <div class="adminBoard">
                    <a href=products/>
                        <div class="adminOption">
                            <h2>Products Management</h2>
                        </div>
                    </a>
                    <a href=users/>
                        <div class="adminOption">
                            <h2>Users Management</h2>
                        </div>
                    </a>
                    <a href=orders/>
                        <div class="adminOption">
                            <h2>Orders Management</h2>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </body>
</html>