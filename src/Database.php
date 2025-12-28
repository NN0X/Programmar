<?php

class Database
{
        private $username;
        private $password;
        private $host;
        private $database;

        public function __construct()
        {
                $this->username = getenv('PG_USER');
                $this->password = getenv('PG_PASSWORD');
                $this->host = getenv('PG_HOST');
                $this->database = getenv('PG_DB');
        }

        public function connect()
        {
                try
                {
                        $conn = new PDO(
                                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                                $this->username,
                                $this->password
                        );
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        return $conn;
                }
                catch(PDOException $e)
                {
                        error_log("Database Connection Error: " . $e->getMessage());
                        die("A technical error occurred. Please try again later.");
                }
        }
}
