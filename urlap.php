<?php
session_start();

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Űrlap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Autó Adatok Űrlap</h1>
    
    <form action="feldolgoz.php" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Általános adatok</legend>
            <label for="vetelar">Vételár (Ft):</label>
            <input type="number" id="vetelar" name="vetelar" required><br><br>

            <label for="ar_eur">Ár (EUR):</label>
            <input type="number" id="ar_eur" name="ar_eur" required><br><br>

            <label for="evjarat">Évjárat:</label>
            <input type="text" id="evjarat" name="evjarat" required><br><br>

            <label for="allapot">Állapot:</label>
            <input type="text" id="allapot" name="allapot" required><br><br>
        </fieldset>

        <fieldset>
            <legend>Jármű adatok</legend>
            <label for="futott_km">Futott km:</label>
            <input type="number" id="futott_km" name="futott_km" required><br><br>

            <label for="kivitel">Kivitel:</label>
            <input type="text" id="kivitel" name="kivitel" required><br><br>

            <label for="szemelyek_szama">Szállítható személyek száma:</label>
            <input type="number" id="szemelyek_szama" name="szemelyek_szama" required><br><br>

            <label for="ajtok_szama">Ajtók száma:</label>
            <input type="number" id="ajtok_szama" name="ajtok_szama" required><br><br>

            <label for="szin">Szín:</label>
            <input type="text" id="szin" name="szin" required><br><br>

            <label for="sajat_tomeg">Saját tömeg (kg):</label>
            <input type="number" id="sajat_tomeg" name="sajat_tomeg" required><br><br>

            <label for="ossztomeg">Össztömeg (kg):</label>
            <input type="number" id="ossztomeg" name="ossztomeg" required><br><br>

            <label for="csomagtarto">Csomagtartó (liter):</label>
            <input type="number" id="csomagtarto" name="csomagtarto" required><br><br>

            <label for="klima">Klíma fajtája:</label>
            <input type="text" id="klima" name="klima" required><br><br>
        </fieldset>

        <fieldset>
            <legend>Motor adatok</legend>
            <label for="uzemanyag">Üzemanyag:</label>
            <input type="text" id="uzemanyag" name="uzemanyag" required><br><br>

            <label for="hengerurtartalom">Hengerűrtartalom (cm³):</label>
            <input type="number" id="hengerurtartalom" name="hengerurtartalom" required><br><br>

            <label for="teljesitmeny">Teljesítmény (LE):</label>
            <input type="number" id="teljesitmeny" name="teljesitmeny" required><br><br>

            <label for="henger_elrendezes">Henger elrendezés:</label>
            <input type="text" id="henger_elrendezes" name="henger_elrendezes" required><br><br>

            <label for="hajtas">Hajtás:</label>
            <input type="text" id="hajtas" name="hajtas" required><br><br>

            <label for="sebvalto">Sebességváltó fajtája:</label>
            <input type="text" id="sebessegvalto" name="sebessegvalto" required><br><br>
        </fieldset>

        <fieldset>
            <legend>Okmányok</legend>
            <label for="okmany_jellege">Okmányok jellege:</label>
            <input type="text" id="okmanyok_jellege" name="okmanyok_jellege" required><br><br>

            <label for="muszaki_vizsga">Műszaki vizsga érvényes:</label>
            <input type="date" id="muszaki_vizsga" name="muszaki_vizsga" required><br><br>
        </fieldset>

        <fieldset>
            <legend>Abroncs</legend>
            <label for="gumi_meret">Nyári gumi mérete:</label>
            <input type="text" id="nyari_gumi_meret" name="nyari_gumi_meret" required><br><br>
        </fieldset>
        <fieldset>
            <legend>Leírás</legend>
            <label for="leiras">Autó leírása:</label>
            <input type="text" id="leiras" name="leiras" required><br><br>
        </fieldset>

        <fieldset>
            <legend>Képfeltöltés</legend>
            <label for="auto_kepek">Autó képeinek feltöltése:</label>
            <input type="file" id="auto_kepek" name="auto_kepek[]" accept="image/*" multiple required><br><br>
        </fieldset>

        <input type="submit" value="Adatok beküldése">
        
    </form>
    <form action="logout.php" method="post">
        <input type="submit" value="Kijelentkezés">
    </form>
</body>
</html>
