<?php
session_start();

// Kapcsolódás az adatbázishoz
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_adatok";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolódási hiba ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Felhasználónév és jelszó fogadása
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Felhasználó lekérdezése az adatbázisból
    $sql = "SELECT * FROM felhasznalok WHERE nev = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['jelszo'])) {
            $_SESSION['user'] = $user;
            echo "Sikeres belépés! Üdvözlünk, " . $user['nev'];
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "A felhasználó nem létezik!";
    }
    
    $stmt->close();
}

$conn->close();
?>
