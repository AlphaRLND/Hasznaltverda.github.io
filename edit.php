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
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolat hiba: " . $conn->connect_error);
}

// Új autó adatok mentése
if (isset($_POST['submit_new'])) {
    $vetelar = $_POST['vetelar'];
    $ar_eur = $_POST['ar_eur'];
    $evjarat = $_POST['evjarat'];
    $allapot = $_POST['allapot'];
    $futott_km = $_POST['futott_km'];
    $kivitel = $_POST['kivitel'];
    $szemelyek_szama = $_POST['szemelyek_szama'];
    $ajtok_szama = $_POST['ajtok_szama'];
    $szin = $_POST['szin'];
    $sajat_tomeg = $_POST['sajat_tomeg'];
    $ossztomeg = $_POST['ossztomeg'];
    $csomagtarto = $_POST['csomagtarto'];
    $klima = $_POST['klima'];
    
    // Motor adatok
    $uzemanyag = $_POST['uzemanyag'];
    $hengerurtartalom = $_POST['hengerurtartalom'];
    $teljesitmeny = $_POST['teljesitmeny'];
    $henger_elrendezese = $_POST['henger_elrendezese'];
    $hajtas = $_POST['hajtas'];
    $sebessegvalto = $_POST['sebessegvalto'];
    
    // Okmányok
    $okmanyok_jellege = $_POST['okmanyok_jellege'];
    $muszaki_vizsga = $_POST['muszaki_vizsga'];

    // Abroncs
    $nyari_gumi_meret = $_POST['nyari_gumi_meret'];

    //Leírás
    $leiras = $_POST['leiras'];

    
    // SQL frissítés lekérdezés
    $sql = "UPDATE autok SET vetelar=?, ar_eur=?, evjarat=?, allapot=?, futott_km=?, kivitel=?, szemelyek_szama=?, ajtok_szama=?, szin=?, sajat_tomeg=?, ossztomeg=?, csomagtarto=?, klima=?, uzemanyag=?, hengerurtartalom=?, teljesitmeny=?, henger_elrendezese=?, hajtas=?, sebessegvalto=?, okmanyok_jellege=?, muszaki_vizsga=?, nyari_gumi_meret=?, leiras=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddisisiisddiisdisssssss", $vetelar, $ar_eur, $evjarat, $allapot, $futott_km, $kivitel, $szemelyek_szama, $ajtok_szama, $szin, $sajat_tomeg, $ossztomeg, $csomagtarto, $klima, $uzemanyag, $hengerurtartalom, $teljesitmeny, $henger_elrendezese, $hajtas, $sebessegvalto, $okmanyok_jellege, $muszaki_vizsga, $nyari_gumi_meret, $leiras, $auto_id);
    
    if ($stmt->execute()) {
        echo "Új autó adatai sikeresen mentésre kerültek.";
        header('Location: dashboard.php'); // Átirányítás a dashboard oldalra
        exit;
    } else {
        echo "Hiba történt az új autó adatainak mentésekor: " . $stmt->error;
    }

    $stmt->close();
}


// Módosítás végrehajtása
if (isset($_POST['submit_edit'])) {
    $auto_id = $_POST['id'];
    $vetelar = $_POST['vetelar'];
    $ar_eur = $_POST['ar_eur'];
    $evjarat = $_POST['evjarat'];
    $allapot = $_POST['allapot'];
    $futott_km = $_POST['futott_km'];
    $kivitel = $_POST['kivitel'];
    $szemelyek_szama = $_POST['szemelyek_szama'];
    $ajtok_szama = $_POST['ajtok_szama'];
    $szin = $_POST['szin'];
    $sajat_tomeg = $_POST['sajat_tomeg'];
    $ossztomeg = $_POST['ossztomeg'];
    $csomagtarto = $_POST['csomagtarto'];
    $klima = $_POST['klima'];

    // Motor adatok
    $uzemanyag = $_POST['uzemanyag'];
    $hengerurtartalom = $_POST['hengerurtartalom'];
    $teljesitmeny = $_POST['teljesitmeny'];
    $henger_elrendezese = $_POST['henger_elrendezese'];
    $hajtas = $_POST['hajtas'];
    $sebessegvalto = $_POST['sebessegvalto'];

    // Okmányok
    $okmanyok_jellege = $_POST['okmanyok_jellege'];
    $muszaki_vizsga = $_POST['muszaki_vizsga'];

    // Abroncs
    $nyari_gumi_meret = $_POST['nyari_gumi_meret'];

    // SQL frissítés lekérdezés
    $sql = "UPDATE autok SET vetelar=?, ar_eur=?, evjarat=?, allapot=?, futott_km=?, kivitel=?, szemelyek_szama=?, ajtok_szama=?, szin=?, sajat_tomeg=?, ossztomeg=?, csomagtarto=?, klima=?, uzemanyag=?, hengerurtartalom=?, teljesitmeny=?, henger_elrendezese=?, hajtas=?, sebessegvalto=?, okmanyok_jellege=?, muszaki_vizsga=?, nyari_gumi_meret=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddssisiisddiisddssssssi", $vetelar, $ar_eur, $evjarat, $allapot, $futott_km, $kivitel, $szemelyek_szama, $ajtok_szama, $szin, $sajat_tomeg, $ossztomeg, $csomagtarto, $klima, $uzemanyag, $hengerurtartalom, $teljesitmeny, $henger_elrendezese, $hajtas, $sebessegvalto, $okmanyok_jellege, $muszaki_vizsga, $nyari_gumi_meret, $auto_id);
    
    if ($stmt->execute()) {
        // Módosítás sikeres, irányítás a dashboard-ra
        header('Location: dashboard.php'); // Átirányítás a dashboard oldalra
        exit; // Ne folytasd a kód végrehajtását
    } else {
        echo "Hiba történt a módosítás során: " . $stmt->error;
    }

    $stmt->close();
}

// Az autó adatait betöltjük a módosításhoz
if (isset($_GET['id'])) {
    $auto_id = $_GET['id'];
    $sql = "SELECT * FROM autok WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $auto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $adatok = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Nem található az autó ID.");
}

// Kapcsolat lezárása
$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autó Módosítása</title>
</head>
<body>
    <h1>Új Autó Felvétel</h1>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?= isset($adatok) ? htmlspecialchars($adatok['id']) : '' ?>">
        <label for="vetelar">Vételár:</label>
        <input type="text" id="vetelar" name="vetelar" value="<?= isset($adatok) ? htmlspecialchars($adatok['vetelar']) : '' ?>" required>
        <br>
        <label for="ar_eur">Ár (EUR):</label>
        <input type="text" id="ar_eur" name="ar_eur" value="<?= isset($adatok) ? htmlspecialchars($adatok['ar_eur']) : '' ?>" required>
        <br>
        <label for="evjarat">Évjárat:</label>
        <input type="text" id="evjarat" name="evjarat" value="<?= isset($adatok) ? htmlspecialchars($adatok['evjarat']) : '' ?>" required>
        <br>
        <label for="allapot">Állapot:</label>
        <input type="text" id="allapot" name="allapot" value="<?= isset($adatok) ? htmlspecialchars($adatok['allapot']) : '' ?>" required>
        <br>
        <label for="futott_km">Futott km:</label>
        <input type="text" id="futott_km" name="futott_km" value="<?= isset($adatok) ? htmlspecialchars($adatok['futott_km']) : '' ?>" required>
        <br>
        <label for="kivitel">Kivitel:</label>
        <input type="text" id="kivitel" name="kivitel" value="<?= isset($adatok) ? htmlspecialchars($adatok['kivitel']) : '' ?>" required>
        <br>
        <label for="szemelyek_szama">Személyek száma:</label>
        <input type="text" id="szemelyek_szama" name="szemelyek_szama" value="<?= isset($adatok) ? htmlspecialchars($adatok['szemelyek_szama']) : '' ?>" required>
        <br>
        <label for="ajtok_szama">Ajtók száma:</label>
        <input type="text" id="ajtok_szama" name="ajtok_szama" value="<?= isset($adatok) ? htmlspecialchars($adatok['ajtok_szama']) : '' ?>" required>
        <br>
        <label for="szin">Szín:</label>
        <input type="text" id="szin" name="szin" value="<?= isset($adatok) ? htmlspecialchars($adatok['szin']) : '' ?>" required>
        <br>
        <label for="sajat_tomeg">Saját tömeg:</label>
        <input type="text" id="sajat_tomeg" name="sajat_tomeg" value="<?= isset($adatok) ? htmlspecialchars($adatok['sajat_tomeg']) : '' ?>" required>
        <br>
        <label for="ossztomeg">Össztömeg:</label>
        <input type="text" id="ossztomeg" name="ossztomeg" value="<?= isset($adatok) ? htmlspecialchars($adatok['ossztomeg']) : '' ?>" required>
        <br>
        <label for="csomagtarto">Csomagtartó:</label>
        <input type="text" id="csomagtarto" name="csomagtarto" value="<?= isset($adatok) ? htmlspecialchars($adatok['csomagtarto']) : '' ?>" required>
        <br>
        <label for="klima">Klíma:</label>
        <input type="text" id="klima" name="klima" value="<?= isset($adatok) ? htmlspecialchars($adatok['klima']) : '' ?>" required>
        <br>

        <!-- Motor adatok -->
        <h2>Motor adatok</h2>
        <label for="uzemanyag">Üzemanyag:</label>
        <input type="text" id="uzemanyag" name="uzemanyag" value="<?= isset($adatok) ? htmlspecialchars($adatok['uzemanyag']) : '' ?>" required>
        <br>
        <label for="hengerurtartalom">Hengerűrtartalom:</label>
        <input type="text" id="hengerurtartalom" name="hengerurtartalom" value="<?= isset($adatok) ? htmlspecialchars($adatok['hengerurtartalom']) : '' ?>" required>
        <br>
        <label for="teljesitmeny">Teljesítmény:</label>
        <input type="text" id="teljesitmeny" name="teljesitmeny" value="<?= isset($adatok) ? htmlspecialchars($adatok['teljesitmeny']) : '' ?>" required>
        <br>
        <label for="henger_elrendezese">Henger elrendezés:</label>
        <input type="text" id="henger_elrendezese" name="henger_elrendezese" value="<?= isset($adatok) ? htmlspecialchars($adatok['henger_elrendezese']) : '' ?>" required>
        <br>
        <label for="hajtas">Hajtás:</label>
        <input type="text" id="hajtas" name="hajtas" value="<?= isset($adatok) ? htmlspecialchars($adatok['hajtas']) : '' ?>" required>
        <br>
        <label for="sebessegvaltoko">Sebességváltó fajtája:</label>
        <input type="text" id="sebessegvalto" name="sebessegvalto" value="<?= isset($adatok) ? htmlspecialchars($adatok['sebessegvalto']) : '' ?>" required>
        <br>

        <!-- Okmányok -->
        <h2>Okmányok</h2>
        <label for="okmanyok_jellege">Okmányok jellege:</label>
        <input type="text" id="okmanyok_jellege" name="okmanyok_jellege" value="<?= isset($adatok) ? htmlspecialchars($adatok['okmanyok_jellege']) : '' ?>" required>
        <br>
        <label for="muszaki_vizsga">Műszaki vizsga érvényes:</label>
        <input type="date" id="muszaki_vizsga" name="muszaki_vizsga" value="<?= isset($adatok) ? htmlspecialchars($adatok['muszaki_vizsga']) : '' ?>" required>
        <br>

        <!-- Abroncs -->
        <h2>Abroncs</h2>
        <label for="nyari_gumi_meret">Nyári gumi mérete:</label>
        <input type="text" id="nyari_gumi_meret" name="nyari_gumi_meret" value="<?= isset($adatok) ? htmlspecialchars($adatok['nyari_gumi_meret']) : '' ?>" required>
        <br>

        <input type="submit" name="<?= isset($adatok) ? 'submit_edit' : 'submit_new' ?>" value="<?= isset($adatok) ? 'Módosítás' : 'Új autó felvétele' ?>">
    </form>
</body>
</html>
