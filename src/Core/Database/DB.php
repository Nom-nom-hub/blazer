<?php

namespace Blazer\Core\Database;

class DB {
    private static $instance;
    private $pdo;
    
    public function __construct() {
        $this->pdo = new \PDO(
            $_ENV['DB_CONNECTION'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD']
        );
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public static function query($sql, $params = []) {
        if (!self::$instance) {
            self::$instance = new self();
        }
        $stmt = self::$instance->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
} 