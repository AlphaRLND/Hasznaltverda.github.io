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

// Autó azonosító lekérése
$auto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lekérdezés az autó adatainak lekéréséhez
$sql = "SELECT id, vetelar, ar_eur, evjarat, allapot, futott_km, kivitel, szemelyek_szama, ajtok_szama, szin, 
        sajat_tomeg, ossztomeg, csomagtarto, klima, uzemanyag, hengerurtartalom, teljesitmeny, 
        henger_elrendezese, hajtas, sebessegvalto, okmanyok_jellege, muszaki_vizsga, nyari_gumi_meret, leiras 
        FROM autok WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $auto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Nincs ilyen autó az adatbázisban.";
    exit;
}

$adatok = $result->fetch_assoc();

// Képek lekérdezése
$sql_kepek = "SELECT file_path FROM kepek WHERE auto_id = ?";
$stmt_kepek = $conn->prepare($sql_kepek);
$stmt_kepek->bind_param("i", $auto_id);
$stmt_kepek->execute();
$result_kepek = $stmt_kepek->get_result();

$kepek = [];
while ($kep = $result_kepek->fetch_assoc()) {
    $kepek[] = $kep['file_path'];
}

// Kapcsolat lezárása
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <title><?= htmlspecialchars($adatok['leiras']) ?></title>
</head>
<body>
    <!-- Navigáció -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Kofferautó</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="#kapcsolat" class="nav-link">Kapcsolat</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Autó neve -->
        <h1><?= htmlspecialchars($adatok['leiras']) ?></h1>

        <!-- Képgaléria -->
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php if (count($kepek) > 0): ?>
                    <?php foreach ($kepek as $index => $kep): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars($kep) ?>" class="d-block w-100" alt="Autó kép">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="carousel-item active">
                        <img src="placeholder.jpg" class="d-block w-100" alt="Nincs kép">
                    </div>
                <?php endif; ?>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Előző</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Következő</span>
            </a>
        </div>

        <!-- Részletes leírás -->
        <div class="mt-5">
            <table class="table">
                <tbody>
                    <tr>
                        <th colspan="2">Általános adatok</th>
                    </tr>
                    <tr>
                        <td>Vételár:</td>
                        <td><?= htmlspecialchars($adatok['vetelar']) ?> Ft (<?= htmlspecialchars($adatok['ar_eur']) ?> €)</td>
                    </tr>
                    <tr>
                        <td>Évjárat:</td>
                        <td><?= htmlspecialchars($adatok['evjarat']) ?></td>
                    </tr>
                    <tr>
                        <td>Állapot:</td>
                        <td><?= htmlspecialchars($adatok['allapot']) ?></td>
                    </tr>
                    <!-- Jármű adatok -->
                    <tr>
                        <th colspan="2">Jármű adatok</th>
                    </tr>
                    <tr>
                        <td>Futott km:</td>
                        <td><?= htmlspecialchars($adatok['futott_km']) ?> km</td>
                    </tr>
                    <tr>
                        <td>Kivitel:</td>
                        <td><?= htmlspecialchars($adatok['kivitel']) ?></td>
                    </tr>
                    <tr>
                        <td>Szállítható szem. száma:</td>
                        <td><?= htmlspecialchars($adatok['szemelyek_szama']) ?> fő</td>
                    </tr>
                    <tr>
                        <td>Ajtók száma:</td>
                        <td><?= htmlspecialchars($adatok['ajtok_szama']) ?></td>
                    </tr>
                    <tr>
                        <td>Szín:</td>
                        <td><?= htmlspecialchars($adatok['szin']) ?></td>
                    </tr>
                    <tr>
                        <td>Saját tömeg:</td>
                        <td><?= htmlspecialchars($adatok['sajat_tomeg']) ?> kg</td>
                    </tr>
                    <tr>
                        <td>Össztömeg:</td>
                        <td><?= htmlspecialchars($adatok['ossztomeg']) ?> kg</td>
                    </tr>
                    <tr>
                        <td>Csomagtartó:</td>
                        <td><?= htmlspecialchars($adatok['csomagtarto']) ?> liter</td>
                    </tr>
                    <tr>
                        <td>Klíma fajtája:</td>
                        <td><?= htmlspecialchars($adatok['klima']) ?></td>
                    </tr>
                    <!-- Motor adatok -->
                    <tr>
                        <th colspan="2">Motor adatok</th>
                    </tr>
                    <tr>
                        <td>Üzemanyag:</td>
                        <td><?= htmlspecialchars($adatok['uzemanyag']) ?></td>
                    </tr>
                    <tr>
                        <td>Hengerűrtartalom:</td>
                        <td><?= htmlspecialchars($adatok['hengerurtartalom']) ?> cm³</td>
                    </tr>
                    <tr>
                        <td>Teljesítmény:</td>
                        <td><?= htmlspecialchars($adatok['teljesitmeny']) ?> LE</td>
                    </tr>
                    <tr>
                        <td>Henger elrendezés:</td>
                        <td><?= htmlspecialchars($adatok['henger_elrendezese']) ?></td>
                    </tr>
                    <tr>
                        <td>Hajtás:</td>
                        <td><?= htmlspecialchars($adatok['hajtas']) ?></td>
                    </tr>
                    <tr>
                        <td>Sebességváltó fajtája:</td>
                        <td><?= htmlspecialchars($adatok['sebessegvalto']) ?></td>
                    </tr>
                    <!-- Okmányok -->
                    <tr>
                        <th colspan="2">Okmányok</th>
                    </tr>
                    <tr>
                        <td>Okmányok jellege:</td>
                        <td><?= htmlspecialchars($adatok['okmanyok_jellege']) ?></td>
                    </tr>
                    <tr>
                        <td>Műszaki vizsga érvényes:</td>
                        <td><?= htmlspecialchars($adatok['muszaki_vizsga']) ?></td>
                    </tr>
                    <!-- Abroncs -->
                    <tr>
                        <th colspan="2">Abroncs</th>
                    </tr>
                    <tr>
                        <td>Nyári gumi mérete:</td>
                        <td><?= htmlspecialchars($adatok['nyari_gumi_meret']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-5 py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">
                Mór, Dózsa György u. 78 <br />
                Telefon: (+36) 20/5101720 (+36) 20/5405216 <br />
                Nyitvatartás: H-P: 9-17 SZ: Zárva tartunk V: Zárva tartunk <br>
                Email: <a href="mailto:info@kofferauto.hu">info@kofferauto.hu</a>
            </p>
        </div>
        <div>
            <p class="text-center text-white">Tekintse meg a Google Térképet: <a href="https://www.google.com/maps/place/Mór,+Dózsa+György+u.+78,+8060" target="_blank">Mór, Dózsa György u. 78 térképe</a></p>
        </div>
    </footer>

    <script src="assets/bootstrap/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
