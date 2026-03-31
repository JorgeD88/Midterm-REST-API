<?php

class Database {
    private $host = 'db.nmbjhwzhuporyoqvxiih.supabase.co';
    private $port = '5432';
    private $db_name = 'postgres';
    private $username = 'postgres';
    private $password = '@K1ll3r176Jd2000';
    public $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'pgsql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Database Connection Error']);
            exit;
        }

        return $this->conn;
    }
}
