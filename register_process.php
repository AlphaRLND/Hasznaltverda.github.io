<?php
// Adatbázis kapcsolat beállítása
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_adatok";

$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrizd, hogy létrejött-e a kapcsolat
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// Ellenőrizd, hogy az űrlapot POST metódussal küldték-e be
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // Ellenőrizd a felhasználónév létezését és jelszavát az adatbázisban
    $query = "SELECT jelszo FROM felhasznalok WHERE nev = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Ellenőrizd a jelszó egyezését
        if (password_verify($password, $row['jelszo'])) {
            // Bejelentkezés sikeres
            session_start();
            $_SESSION['username'] = $username; // Session létrehozása

            // Átirányítás a főoldalra
            header("Location: proba.php");
            exit();
        } else {
            echo "Hibás jelszó!";
        }
    } else {
        echo "Nincs ilyen felhasználónév!";
    }

    $stmt->close();
}

$conn->close();
?>
