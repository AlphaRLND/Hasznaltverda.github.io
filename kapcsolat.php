<?php
// kapcsolat.php

// Adatbázis-kapcsolódási adatok
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_adatok";

// Kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolat hiba: " . $conn->connect_error);
}
?>
