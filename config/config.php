<?php
$servername = "localhost";
$name = "root";
$password = "root";
$dbname = "sneaker_corner";

$conn = new mysqli($servername, $name, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
