<?php
namespace App;

use PDO;

class Database
{
    private $server = 'localhost';
    private $name = 'e_test';
    private $username = 'root';
    private $password = '';

    public function __construct()
    {
        return $this->connect();
    }

    private function connect()
    {
        return new PDO("mysql:host={$this->server};dbname={$this->name}",$this->username, $this->password,[ PDO::MYSQL_ATTR_FOUND_ROWS => true ]);
    }

    public function columnsIn()
    {
        $db = $this->connect();

        $query = $db->query("
                SELECT COLUMN_NAME
                FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $this->name . "'
                AND TABLE_NAME = '" . $this->table . "'
            ");

        while($row = $query->fetch()) {
            $result[] = $row;
        }

        return array_column($result, 'COLUMN_NAME');
    }

}
