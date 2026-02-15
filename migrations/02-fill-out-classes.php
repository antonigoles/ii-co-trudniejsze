<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();

    // get classes json
    $data = json_decode(file_get_contents(__DIR__ . '/../class_list.json'), true);
    $categories = array_keys($data);

    foreach ($categories as $category) {
        foreach ($data[$category] as $class) {
            $connection->query(
                "INSERT INTO classes (category, name) VALUES (:category, :name)", 
                [
                    "category" => $category,
                    "name" => $class
                ]
            );
        }
    }
?>