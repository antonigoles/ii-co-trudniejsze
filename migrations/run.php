<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();

    $migrations_table = "CREATE TABLE IF NOT EXISTS migrations (name text, runAt timestamp);";
    $connection->query($migrations_table, []);

    $run_migrations = $connection->query("SELECT name FROM migrations;", []);
    $run_migrations = array_map(static fn ($row) => $row['name'], $run_migrations);
    $iterator = new FilesystemIterator(__DIR__, FilesystemIterator::SKIP_DOTS);


    $non_migration_scripts = ['run.php', 'clear-database.php'];

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile()) {
            $migration_name = $fileInfo->getFilename();
            
            if (in_array($migration_name, $non_migration_scripts)) continue;

            if (in_array($migration_name, $run_migrations)) {
                echo "Skipping " . $migration_name . "\n";
                continue;
            }

            require($fileInfo->getRealPath());
            echo "Successfully run " . $migration_name . "\n";

            $connection->query(
                "INSERT INTO migrations VALUES (:name, NOW())", 
                [
                    "name" => $migration_name
                ]
            );
        }
    }
?>