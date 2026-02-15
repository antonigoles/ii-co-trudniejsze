<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();

    $categories = $connection->query_field("SELECT DISTINCT category FROM classes;", [], 'category');

    foreach ($categories as $category) {
        $classes = $connection->query_field(
            "SELECT id FROM classes WHERE category = :category;", 
            [
                "category" => $category
            ], 
            'id'
        );

        $classes_size = count($classes);

        for ($i = 0; $i < $classes_size; $i++) {
            for ($j = $i + 1; $j < $classes_size; $j++) {
                $class_a = $classes[$i];
                $class_b = $classes[$j];
                $connection->query(
                    "INSERT INTO questions (option_a, option_b) VALUES (:class_a, :class_b)",
                    [
                        "class_a" => $class_a,
                        "class_b" => $class_b
                    ]
                );
            }
        }
    }
?>