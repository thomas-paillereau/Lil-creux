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
            $queryTransactions = 'SELECT * FROM transactions WHERE finish = 0 ORDER BY transaction_id';
            $statement1 = $db->prepare($queryTransactions);
            $statement1->execute();
            $transactions = $statement1->fetchAll();
            $statement1->closeCursor();

            $price = 0;
            foreach ($transactions as $transaction) : ?>
                <?php echo '<div class="row">';?>
                <?php echo "|" . $transaction['transaction_id'] . "| " . $transaction['transaction_date'] . "";?>
                <form id="user" action="end_order.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $transaction['transaction_id']; ?>">
                    <input type="hidden" name="finish" value="<?php echo $transaction['finish']; ?>">
                    <button type="submit">Finish</button>
                </form>
                </div>
            <?php endforeach;?>
    </div>
</main>
<footer></footer>
</body>
</html>