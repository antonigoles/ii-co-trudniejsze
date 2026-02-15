<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();

    $tables_to_drop = ['migrations'];

    foreach ($tables_to_drop as $table) {
        $connection->query(
            "DROP TABLE IF EXISTS $table", 
            []
        );

        echo "\"$table\"" . ' table dropped' . "\n";
    }
?>