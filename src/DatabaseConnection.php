<?php

namespace App;

class DatabaseConnection 
{
    private static DatabaseConnection $instance;

    private \PDO $pdo;

    private function __construct(
        private string $host,
        private string $port,
        private string $database,
        private string $username,
        private string $password
    ) {}

    private function initialize_connection(): void
    {
        $connection_string = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s",
            $this->host,
            $this->port,
            $this->database
        );

        $this->pdo = new \PDO(
            $connection_string,
            $this->username, 
            $this->password
        );
    }

    public static function get(): DatabaseConnection
    {
        if (!isset(self::$instance)) {
            self::$instance = new DatabaseConnection(
                getenv('DB_HOST') ?: 'localhost',
                getenv('DB_PORT') ?: '5432',
                getenv('DB_DATABASE') ?: 'main',
                getenv('DB_USERNAME') ?: 'root',
                getenv('DB_PASSWORD') ?: 'abc_root_123',
            );

            try {
                self::$instance->initialize_connection();
            } catch (\PDOException $e) {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$instance;
    }

    /**
     * Make any query
     * @param string $query
     * @param array $params
     * @return array
     */
    public function query(string $query, array $params): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Run query and retrieve specific field as a list
     * @param string $query
     * @param array $params
     * @return array
     */
    public function query_field(string $query, array $params, string $field): array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return array_map(
            function ($row) use ($field) { 
                return $row[$field];
            }, 
            $stmt->fetchAll(\PDO::FETCH_ASSOC)
        );
    }
}

?>