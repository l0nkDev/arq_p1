<?php

use Pdo\Pgsql;


class Connect{
    public static function connection(){
        $dsn = "pgsql:host=localhost;port=5432;dbname=arqp1db;user=postgres;password=postgres";
        $connection = new Pgsql($dsn);
        $connection->query("SET NAMES 'utf8'");
        return $connection;
    }
}