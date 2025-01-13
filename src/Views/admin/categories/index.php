<?php

require_once __DIR__ . '../../../../Config/DatabaseConnection.php';

use App\Config\DatabaseConnection;

$db = new DatabaseConnection();
$connexion = $db->connect();

?>