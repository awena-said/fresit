<?php

$servername = "localhost";
$username = "webdev";
$password = "W3bD£velopment";
$dbname = "royal_drawing_school";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
