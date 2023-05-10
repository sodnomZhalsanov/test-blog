<?php
namespace App;
use PDO;
interface PDOconnection {
    public function setConnection(PDO $connection);
}