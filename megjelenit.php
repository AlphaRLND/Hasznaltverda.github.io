<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Kapcsolat létrehozása
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_adatok";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kapcsolat hiba: " . $conn->connect_error);
}

// Lapozási változók
$autok_per_oldal = 3; // Hány autó jelenjen meg egy oldalon
$oldal = isset($_GET['oldal']) ? (int)$_GET['oldal'] : 1; // Aktuális oldal

if ($oldal < 1) {
    $oldal = 1;
}

// Az adatok kezdő sora a lekérdezésben
$offset = ($oldal - 1) * $autok_per_oldal;

// Szűrő feltételek
$where = [];
if (!empty($_GET['marka'])) {
    $marka = $conn->real_escape_string($_GET['marka']);
    $where[] = "SUBSTRING_INDEX(leiras, ' ', 1) = '$marka'";
}
if (!empty($_GET['uzemanyag'])) {
    $uzemanyag = $conn->real_escape_string($_GET['uzemanyag']);
    $where[] = "uzemanyag = '$uzemanyag'";
}
if (!empty($_GET['valto'])) {
    $valto = $conn->real_escape_string($_GET['valto']);
    $where[] = "sebessegvalto = '$valto'";
}
if (!empty($_GET['min_ar'])) {
    $min_ar = (int)$_GET['min_ar'];
    $where[] = "vetelar >= $min_ar";
}
if (!empty($_GET['max_ar'])) {
    $max_ar = (int)$_GET['max_ar'];
    $where[] = "vetelar <= $max_ar";
}
if (!empty($_GET['min_ev'])) {
    $min_ev = (int)$_GET['min_ev'];
    $where[] = "evjarat >= $min_ev";
}
if (!empty($_GET['max_ev'])) {
    $max_ev = (int)$_GET['max_ev'];
    $where[] = "evjarat <= $max_ev";
}

// SQL lekérdezés készítése az összes autó számának lekérdezésére
$sql_osszes = "SELECT COUNT(*) AS osszes_autok FROM autok";
if ($where) {
    $sql_osszes .= " WHERE " . implode(" AND ", $where);
}

// Összes autó számának lekérdezése
$result_osszes = $conn->query($sql_osszes);
if ($result_osszes === false) {
    die("Hiba a lekérdezés során: " . $conn->error);
}
$row_osszes = $result_osszes->fetch_assoc();
$osszes_autok = $row_osszes['osszes_autok'];

// Összes oldal kiszámítása
$osszes_oldal = ceil($osszes_autok / $autok_per_oldal);

// Lekérdezés az adott oldal autóinak lekéréséhez
$sql = "SELECT id, vetelar, ar_eur, evjarat, allapot, futott_km, kivitel, szemelyek_szama, ajtok_szama, szin, 
        sajat_tomeg, ossztomeg, csomagtarto, klima, uzemanyag, hengerurtartalom, teljesitmeny, 
        henger_elrendezese, hajtas, sebessegvalto, okmanyok_jellege, muszaki_vizsga, nyari_gumi_meret, leiras 
        FROM autok";
if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " LIMIT $autok_per_oldal OFFSET $offset"; // Lapozás logika

$result = $conn->query($sql);
if ($result === false) {
    die("Hiba a lekérdezés során: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autók kínálata</title>
    <link rel="stylesheet" href="style_2.css">
    <style>
        
    </style>
</head>
<body>

<h1>Autók kínálata</h1>

<div class="container">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($adatok = $result->fetch_assoc()): ?>
            <div class="card">
                <?php
                // Képek lekérdezése
                $sql_kepek = "SELECT file_path FROM kepek WHERE auto_id = ?";
                $stmt_kepek = $conn->prepare($sql_kepek);
                $stmt_kepek->bind_param("i", $adatok['id']);
                $stmt_kepek->execute();
                $result_kepek = $stmt_kepek->get_result();

                // Kép megjelenítése
                if ($result_kepek->num_rows > 0) {
                    $kep = $result_kepek->fetch_assoc();
                    echo '<img src="' . htmlspecialchars($kep['file_path']) . '" alt="Autó kép">';
                } else {
                    echo '<img src="placeholder.jpg" alt="Nincs kép">';
                }
                ?>
                <h2><a href="view.php?id=<?= htmlspecialchars($adatok['id']) ?>"><?= htmlspecialchars($adatok['leiras']) ?></a></h2>
                <p>Vételár: <?= htmlspecialchars($adatok['vetelar']) ?> Ft (<?= htmlspecialchars($adatok['ar_eur']) ?> €)</p>
                <p>Állapot: <?= htmlspecialchars($adatok['allapot']) ?></p>
                <p>Futott km: <?= htmlspecialchars($adatok['futott_km']) ?> km</p>
                <p>Teljesítmény: <?= htmlspecialchars($adatok['teljesitmeny']) ?> LE</p>
                <p>Üzemanyag: <?= htmlspecialchars($adatok['uzemanyag']) ?></p>
                <p>Szín: <?= htmlspecialchars($adatok['szin']) ?></p>
                <p>Leírás: <?= htmlspecialchars($adatok['leiras']) ?></p>
                
                <a href="view.php?id=<?= htmlspecialchars($adatok['id']) ?>" class="btn">Részletek</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Nincs autó az adatbázisban a megadott feltételek mellett.</p>
    <?php endif; ?>
</div>

<!-- Lapozás -->
<div class="pagination">
    <?php if ($osszes_oldal > 1): ?>
        <?php for ($i = 1; $i <= $osszes_oldal; $i++): ?>
            <a href="?oldal=<?= $i ?><?= !empty($_GET) ? '&' . http_build_query($_GET) : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    <?php endif; ?>
</div>

<?php
// Kapcsolat bezárása
$conn->close();
?>

</body>
</html>
