<?php

namespace App\Core;

use PDO;

use PDOException;

class Database {
    private static $instance = null;

    public static function connect() {
        if (!self::$instance) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
            $dotenv->load();

            $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8";
            try {
                self::$instance = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("DB Error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
