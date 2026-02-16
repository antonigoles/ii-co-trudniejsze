<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use App\DatabaseConnection;

    $connection = DatabaseConnection::get();

    $connection->query(
        "CREATE TABLE IF NOT EXISTS daily_progress
                (
                    owner_id varchar(16) NOT NULL PRIMARY KEY,
                    streak int NOT NULL,
                    todays_progress int,
                    last_update timestamp
                );
        ",
        []
    );

    $connection->query(
        "CREATE INDEX IF NOT EXISTS owner_index ON daily_progress(owner_id)",
        []
    );
?>