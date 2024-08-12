<?php

$inFolder = substr_count($_SERVER['REQUEST_URI'], '/') == 2;
$prefix = ".";
if ($inFolder) {
    $prefix = "..";
}

require_once "$prefix/vendor/autoload.php";
// do not use the .env.example
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, $levels = 1), ".env");
$dotenv->safeLoad();

$mysql_host = $_ENV["mysql_host"];
$mysql_username = $_ENV["mysql_username"];
$mysql_password = $_ENV["mysql_password"];
$mysql_database = $_ENV["mysql_database"];

try {
    $db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database;port=3306", $mysql_username, $mysql_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
