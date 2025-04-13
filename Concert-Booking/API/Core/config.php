<?php
$host = 'localhost';
$port = 8889;
$db_name = 'concert_finder';
$username = 'root';
$password = 'root';

try {
    $db = new PDO("mysql:host=$host;port=$port;dbname=$db_name", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
