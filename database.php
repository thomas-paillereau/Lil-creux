<?php
    session_start();
    $dsn = 'mysql:host=172.21.82.208;dbname=GROUP3';
    $database_username = 'GROUP3';
    $database_password = '439';

    try {
        $db = new PDO($dsn, $database_username, $database_password);
    } catch (PDOException $e) {
        $error_message = $e->getMessage();
        include('database_error.php');
        exit();
    }
?>

