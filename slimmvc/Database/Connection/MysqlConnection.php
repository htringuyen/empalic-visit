<?php
namespace Database\Connection;

use Database\QueryBuilder\QueryBuilder;
use PDO;
use Exception;
use Database\Connection\Connection;
use PDOStatement;

class MysqlConnection implements Connection {
    private PDO $pdo;

    public function __construct($config) {
        [
            "host" => $host,
            "port" => $port,
            "database" => $database,
            "username" => $username,
            "password" => $password
        ] = $config;

        if (empty($host) || empty($username) || empty($port)) {
            throw new Exception("connection incorrectly configured");
        }

        $this->pdo = new PDO("mysql:host={$host};port={$port};database={$database}", $username, $password);
    }

    public function pdo(): PDO {
        return $this->pdo;
    }

    public function executeNamedQuery($query, $params): array
    {
        $stmt = $this->pdo()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryBuilder(): QueryBuilder
    {
        // TODO: Implement queryBuilder() method.
        return false;
    }
}























