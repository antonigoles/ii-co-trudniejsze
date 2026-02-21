<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();

    $connection->query(
        "ALTER TABLE classes
        ADD COLUMN usos_id VARCHAR(255),
        ADD COLUMN major_name VARCHAR(255);
        ",
        []
    );

    $connection->query(
        "CREATE INDEX idx_classes_usos_id ON classes (usos_id);",
        []
    );

    $connection->query(
        "CREATE INDEX idx_classes_major_name ON classes (major_name);",
        []
    );
?>