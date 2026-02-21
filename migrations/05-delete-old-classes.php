<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();

    $categories = $connection->query("DELETE FROM answers", []);
    $categories = $connection->query("DELETE FROM questions", []);
    $categories = $connection->query("DELETE FROM classes", []);
    
?>