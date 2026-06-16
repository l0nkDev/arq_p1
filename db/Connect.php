<?php

use Pdo\Pgsql;


class Connect{

    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = "pgsql:host=localhost;port=5432;dbname=arqp1db;user=postgres;password=postgres";
        try {
            $this->pdo = new Pgsql($dsn);
            $this->pdo->query("SET NAMES 'utf8'");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    private function __clone() {}

    public function __wakeup() {
        throw new Exception("No se puede deserializar un Singleton.");
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Connect();
        }
        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }
}

?>