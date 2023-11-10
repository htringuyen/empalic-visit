<?php
namespace Database\Connection;
use PDO;
use Database\QueryBuilder\QueryBuilder;
use PDOStatement;

interface Connection {
    public function pdo(): PDO;

    public function queryBuilder(): QueryBuilder;

    public function executeNamedQuery($query, $params): array;
}