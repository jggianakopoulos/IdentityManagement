<?php
class Connection {

    private $host = "localhost";
    private $db_name = "identity";
    private $username = "IMApp";
    private $password = "t!5@m9N5QFAv8UWE";
    public $pdo;

    public function __construct(){
        try{
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
        }catch(PDOException $exception){
            $this->pdo = null;
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>
