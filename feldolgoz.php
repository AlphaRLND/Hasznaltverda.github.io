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

try {
    // Kapcsolat létrehozása
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kapcsolat ellenőrzése
    if ($conn->connect_error) {
        throw new Exception("Kapcsolat hiba: " . $conn->connect_error);
    }

    // Adatok beolvasása az űrlapról
    $vetelar = isset($_POST['vetelar']) ? floatval($_POST['vetelar']) : 0;
    $ar_eur = isset($_POST['ar_eur']) ? floatval($_POST['ar_eur']) : 0;
    $evjarat = isset($_POST['evjarat']) ? intval($_POST['evjarat']) : 0;
    $allapot = isset($_POST['allapot']) ? $_POST['allapot'] : '';
    $futott_km = isset($_POST['futott_km']) ? intval($_POST['futott_km']) : 0;
    $kivitel = isset($_POST['kivitel']) ? $_POST['kivitel'] : '';
    $szemelyek_szama = isset($_POST['szemelyek_szama']) ? intval($_POST['szemelyek_szama']) : 0;
    $ajtok_szama = isset($_POST['ajtok_szama']) ? intval($_POST['ajtok_szama']) : 0;
    $szin = isset($_POST['szin']) ? $_POST['szin'] : '';
    $sajat_tomeg = isset($_POST['sajat_tomeg']) ? floatval($_POST['sajat_tomeg']) : 0;
    $ossztomeg = isset($_POST['ossztomeg']) ? floatval($_POST['ossztomeg']) : 0;
    $csomagtarto = isset($_POST['csomagtarto']) ? intval($_POST['csomagtarto']) : 0;
    $klima = isset($_POST['klima']) ? intval($_POST['klima']) : 0;

    // Új mezők adatai
    $uzemanyag = isset($_POST['uzemanyag']) ? $_POST['uzemanyag'] : '';
    $hengerurtartalom = isset($_POST['hengerurtartalom']) ? floatval($_POST['hengerurtartalom']) : 0;
    $teljesitmeny = isset($_POST['teljesitmeny']) ? intval($_POST['teljesitmeny']) : 0;
    $henger_elrendezes = isset($_POST['henger_elrendezes']) ? $_POST['henger_elrendezes'] : '';
    $hajtas = isset($_POST['hajtas']) ? $_POST['hajtas'] : '';
    $sebessegvalto = isset($_POST['sebessegvalto']) ? $_POST['sebessegvalto'] : '';
    $okmanyok_jellege = isset($_POST['okmanyok_jellege']) ? $_POST['okmanyok_jellege'] : '';
    $muszaki_vizsga = isset($_POST['muszaki_vizsga']) ? $_POST['muszaki_vizsga'] : '';
    $nyari_gumi_meret = isset($_POST['nyari_gumi_meret']) ? $_POST['nyari_gumi_meret'] : '';
    $leiras = isset($_POST['leiras']) ? $_POST['leiras'] : '';

    // SQL lekérdezés az adatok beszúrásához
    $sql = "INSERT INTO autok (vetelar, ar_eur, evjarat, allapot, futott_km, kivitel, szemelyek_szama, ajtok_szama, szin, sajat_tomeg, ossztomeg, csomagtarto, klima, uzemanyag, hengerurtartalom, teljesitmeny, henger_elrendezese, hajtas, sebessegvalto, okmanyok_jellege, muszaki_vizsga, nyari_gumi_meret, leiras)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Előkészített lekérdezés
    $stmt = $conn->prepare($sql);
    
    // Ellenőrizzük, hogy a lekérdezés előkészítése sikeres volt
    if ($stmt === false) {
        throw new Exception("Hiba a lekérdezés előkészítése során: " . $conn->error);
    }

    // Bind paraméterek: 6 db double, 6 db int, 10 db string
    $stmt->bind_param("ddisisiisddiisdisssssss", 
        $vetelar, 
        $ar_eur, 
        $evjarat, 
        $allapot, 
        $futott_km, 
        $kivitel, 
        $szemelyek_szama, 
        $ajtok_szama, 
        $szin, 
        $sajat_tomeg, 
        $ossztomeg, 
        $csomagtarto, 
        $klima, 
        $uzemanyag, 
        $hengerurtartalom, 
        $teljesitmeny, 
        $henger_elrendezes, 
        $hajtas, 
        $sebessegvalto, 
        $okmanyok_jellege, 
        $muszaki_vizsga, 
        $nyari_gumi_meret,
        $leiras
    );

    // Lekérdezés végrehajtása
    if ($stmt->execute()) {
        echo "Az adatok sikeresen mentésre kerültek.";
        
        // Az utolsó beszúrt autó ID-jének lekérése
        $last_auto_id = $stmt->insert_id;

        // Kép feltöltés és elérési útvonal mentése
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $uploaded_file_paths = []; // Képek elérési útvonalainak tárolására

        foreach ($_FILES["auto_kepek"]["name"] as $key => $name) {
            // Egyedi fájlnév generálása
            $new_filename = uniqid() . '-' . basename($_FILES["auto_kepek"]["name"][$key]);
            $target_file = $target_dir . $new_filename;
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Ellenőrizzük, hogy a fájl egy valódi kép
            $check = getimagesize($_FILES["auto_kepek"]["tmp_name"][$key]);
            if($check === false) {
                echo "A fájl nem kép.";
                $uploadOk = 0;
            }

            // Fájl méret ellenőrzése (max 20 MB)
            if ($_FILES["auto_kepek"]["size"][$key] > 20000000) {
                echo "A fájl túl nagy.";
                $uploadOk = 0;
            }

            // Csak bizonyos fájltípusok engedélyezettek
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                echo "Csak JPG, JPEG, PNG és GIF fájlok engedélyezettek.";
                $uploadOk = 0;
            }

            // Ellenőrizzük, hogy $uploadOk nem 0
            if ($uploadOk == 0) {
                echo "A fájl nem került feltöltésre.";
            } else {
                if (move_uploaded_file($_FILES["auto_kepek"]["tmp_name"][$key], $target_file)) {
                    echo "A fájl " . htmlspecialchars($new_filename) . " sikeresen feltöltve.";
                    $uploaded_file_paths[] = $target_file; // Elérési útvonal mentése
                } else {
                    echo "Hiba történt a fájl feltöltésekor.";
                }
            }
        }

        // SQL lekérdezés a képek elérési útvonalainak mentésére
        foreach ($uploaded_file_paths as $file_path) {
            $sql_kepek = "INSERT INTO kepek (auto_id, file_path) VALUES (?, ?)";
            $stmt_kepek = $conn->prepare($sql_kepek);
            if ($stmt_kepek === false) {
                throw new Exception("Hiba a képek lekérdezésének előkészítése során: " . $conn->error);
            }
            $stmt_kepek->bind_param("is", $last_auto_id, $file_path);
            $stmt_kepek->execute();
            $stmt_kepek->close();
        }

    } else {
        throw new Exception("Hiba történt: " . $stmt->error);
    }

    // Kapcsolat lezárása
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo $e->getMessage();
}
?>
