<?php
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$servername = "localhost"; // Kapcsolódás localhosthoz
$username = "root"; // Saját adatbázis felhasználói név
$password = ""; // Saját adatbázis jelszó (alapértelmezett XAMPP esetén üres)
$dbname = "auto_adatok"; // Adatbázis neve

// Kapcsolat létrehozása
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolat hiba: " . $conn->connect_error);
}

// Törlés végrehajtása
if (isset($_GET['id'])) {
    $auto_id = $_GET['id'];
    
    // SQL törlés lekérdezés
    $sql = "DELETE FROM autok WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $auto_id);
    
    if ($stmt->execute()) {
        echo "Az autó sikeresen törölve.";
    } else {
        echo "Hiba történt a törlés során: " . $stmt->error;
    }

    $stmt->close();
}

// Irányítás az autók megjelenítése oldalra
header('Location: megjelenit.php');
exit;

// Kapcsolat lezárása
$conn->close();
?>
