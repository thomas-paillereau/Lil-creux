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

if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['imageUpload']['tmp_name'];
    $image_name = basename($_FILES['imageUpload']['name']);
    $target_dir = "../../images/";
    $target_file = $target_dir . $image_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $allowed_types)) {
        die("Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    if (!move_uploaded_file($tmp_name, $target_file)) {
        die("Image upload failed.");
    }
}

else {
    die("No image uploaded or upload error.");
}

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT);
$stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);

$query = 'INSERT INTO product_list (product_name, category, price, stock) VALUES (:name, :category, :price, :stock)';
$statement1 = $db->prepare($query);
$statement1->bindValue(':name', $name);
$statement1->bindValue(':category', $category);
$statement1->bindValue(':price', $price);
$statement1->bindValue(':stock', $stock);
$statement1->execute();
$product_id = $db->lastInsertId(); // Get inserted product ID
$statement1->closeCursor();

// Validate inputs
if ($product_id == NULL) {
    $error_message = "Invalid product data. Check all fields and try again.";
    include('../../database_error.php');
} else {
    session_abort();
    $db = 0;
    require_once('../../database.php');

    $query = '';
    $statement3 = $db->prepare($query);
    $statement3->bindValue(':product_id', $product_id);
    $statement3->execute();
    $statement3->closeCursor();

    header('Location: ../');
    exit();
}

?>