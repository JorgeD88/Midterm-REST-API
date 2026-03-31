<?php

class Database {
    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct() {
        $this->host = getenv('DB_HOST') ?: 'db.nmbjhwzhuporyoqvxiih.supabase.co';
        $this->port = getenv('DB_PORT') ?: '5432';
        $this->db_name = getenv('DB_NAME') ?: 'postgres';
        $this->username = getenv('DB_USER') ?: 'postgres';
        $this->password = getenv('DB_PASSWORD') ?: '@K1ll3r176Jd2000';
    }

    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'pgsql:host=' . $this->host .
                   ';port=' . $this->port .
                   ';dbname=' . $this->db_name .
                   ';sslmode=require';

            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Database Connection Error']);
            exit;
        }

        return $this->conn;
    }
}
