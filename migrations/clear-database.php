<?php
    require_once __DIR__ . '/../vendor/autoload.php';
    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();


    $tables_query = 
        "SELECT table_name
        FROM information_schema.tables
        WHERE table_schema='public'
        AND table_type='BASE TABLE';
    ";

    $tables_to_drop = $connection->query_field($tables_query, [], 'table_name');

    foreach ($tables_to_drop as $table) {
        $connection->query(
            "DROP TABLE IF EXISTS $table CASCADE", 
            []
        );

        echo "\"$table\"" . ' table dropped' . "\n";
    }
?>