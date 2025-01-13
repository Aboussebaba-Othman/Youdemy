<?php
namespace App\Config;
use PDO;
use PDOException;
class DatabaseConnection
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "youdemy";
    private $connexion;

    public function connect() {
        try {
            $this->connexion = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connexion;
            
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            return null;
        }
    }
}
?>