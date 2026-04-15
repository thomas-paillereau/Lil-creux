<?php
    session_start();
    $dsn = 'mysql:host=localhost;dbname=GROUP3';
    $database_username = 'root';
    $database_password = '';

    try {
        $db = new PDO($dsn, $database_username, $database_password);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('database_error.php');
        exit();
    }
?>

