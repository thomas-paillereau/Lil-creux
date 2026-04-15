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

$queryUsers = 'SELECT * FROM users ORDER BY user_id';
$statement1 = $db->prepare($queryUsers);
$statement1->execute();
$users = $statement1->fetchAll();
$statement1->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ecommerce</title>
    <link rel="stylesheet" href="../admin.css"/>
</head>
<body>
<nav></nav>
<header></header>
<main>
    <a href="../../">
        <h1>Main Page</h1>
    </a>
    <a href="../">
        <h1>Admin Main Page</h1>
    </a>
    <div class="admin">
        <?php
            $price = 0;
            foreach ($users as $user) : ?>
                <?php echo '<div class="row">';?>
                <?php echo "|" . $user['user_id'] . "| " . $user['user_name'] . " - " . $user['user_email'] . "";?>
                <form id="user" action="remove_from_users.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $user['user_id']; ?>">
                    <button type="submit">Remove</button>
                </form>
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
                </div>
            <?php endforeach;?>
    </div>
</main>
<footer></footer>
</body>
</html>