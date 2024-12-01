<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ellenőrizzük, hogy a session elindult-e
}
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Adatbázis kapcsolat
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auto_adatok";
$conn = new mysqli($servername, $username, $password, $dbname);

// Ellenőrzés
if ($conn->connect_error) {
    die("Kapcsolat hiba: " . $conn->connect_error);
}

// Szűrő lekérdezés feldolgozása
$where = [];
$has_filter = false; // Flag, hogy ellenőrizzük, van-e szűrő

// Márka szűrés
if (!empty($_GET['marka'])) {
    $marka = $conn->real_escape_string($_GET['marka']);
    if ($marka !== 'ALL') { // Csak akkor szűrj, ha nem "ALL"
        $where[] = "leiras LIKE '$marka%'";
        $has_filter = true;
    }
}

// Egyéb szűrők
if (!empty($_GET['uzemanyag'])) {
    $uzemanyag = $conn->real_escape_string($_GET['uzemanyag']);
    $where[] = "uzemanyag = '$uzemanyag'";
    $has_filter = true;
}
if (!empty($_GET['valto'])) {
    $valto = $conn->real_escape_string($_GET['valto']);
    $where[] = "sebessegvalto = '$valto'";
    $has_filter = true;
}
if (!empty($_GET['min_ar'])) {
    $min_ar = (int)$_GET['min_ar'];
    $where[] = "vetelar >= $min_ar";
    $has_filter = true;
}
if (!empty($_GET['max_ar'])) {
    $max_ar = (int)$_GET['max_ar'];
    $where[] = "vetelar <= $max_ar";
    $has_filter = true;
}
if (!empty($_GET['min_ev'])) {
    $min_ev = (int)$_GET['min_ev'];
    $where[] = "evjarat >= $min_ev";
    $has_filter = true;
}
if (!empty($_GET['max_ev'])) {
    $max_ev = (int)$_GET['max_ev'];
    $where[] = "evjarat <= $max_ev";
    $has_filter = true;
}

// SQL lekérdezés készítése, ha van szűrő
$result = null;
if ($has_filter) {
    $sql = "SELECT * FROM autok";
    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    // Eredmények lekérdezése
    $result = $conn->query($sql);
}

// Töltsük be a leszűrt autókat a megjelenit.php-ból
if ($result && $result->num_rows > 0) {
    $autos = []; // Autók tárolására
    while ($row = $result->fetch_assoc()) {
        $autos[] = $row; // Mentjük az autókat egy tömbbe
    }
} else {
    $autos = []; // Ha nincs találat, üres tömb
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autó Szűrő</title>
    <link rel="stylesheet" href="style_3.css">
    <style>
        
    </style>
</head>
<body>
    <h1>Autó Szűrő</h1>

    <div class="form-container">
        <form action="search_form.php" method="GET">
            <div class="form-group">
                <label for="marka">Márka:</label>
                <select name="marka" id="marka">
                    <option value="ALL">-- Gyártmány --</option>
                    <option value="AUDI" <?= (isset($_GET['marka']) && $_GET['marka'] === 'AUDI') ? 'selected' : '' ?>>AUDI</option>
                    <option value="BMW" <?= (isset($_GET['marka']) && $_GET['marka'] === 'BMW') ? 'selected' : '' ?>>BMW</option>
                    <option value="CITROEN" <?= (isset($_GET['marka']) && $_GET['marka'] === 'CITROEN') ? 'selected' : '' ?>>CITROEN</option>
                    <option value="FIAT" <?= (isset($_GET['marka']) && $_GET['marka'] === 'FIAT') ? 'selected' : '' ?>>FIAT</option>
                    <option value="FORD" <?= (isset($_GET['marka']) && $_GET['marka'] === 'FORD') ? 'selected' : '' ?>>FORD</option>
                    <option value="HONDA" <?= (isset($_GET['marka']) && $_GET['marka'] === 'HONDA') ? 'selected' : '' ?>>HONDA</option>
                    <option value="HYUNDAI" <?= (isset($_GET['marka']) && $_GET['marka'] === 'HYUNDAI') ? 'selected' : '' ?>>HYUNDAI</option>
                    <option value="MERCEDES" <?= (isset($_GET['marka']) && $_GET['marka'] === 'MERCEDES') ? 'selected' : '' ?>>MERCEDES</option>
                    <option value="NISSAN" <?= (isset($_GET['marka']) && $_GET['marka'] === 'NISSAN') ? 'selected' : '' ?>>NISSAN</option>
                    <option value="PEUGEOT" <?= (isset($_GET['marka']) && $_GET['marka'] === 'PEUGEOT') ? 'selected' : '' ?>>PEUGEOT</option>
                    <option value="SKODA" <?= (isset($_GET['marka']) && $_GET['marka'] === 'SKODA') ? 'selected' : '' ?>>SKODA</option>
                    <option value="TOYOTA" <?= (isset($_GET['marka']) && $_GET['marka'] === 'TOYOTA') ? 'selected' : '' ?>>TOYOTA</option>
                </select>
            </div>

            <div class="form-group">
                <label for="uzemanyag">Üzemanyag:</label>
                <select name="uzemanyag" id="uzemanyag">
                    <option value="">-- Üzemanyag --</option>
                    <option value="Benzin" <?= (isset($_GET['uzemanyag']) && $_GET['uzemanyag'] === 'Benzin') ? 'selected' : '' ?>>Benzin</option>
                    <option value="Dízel" <?= (isset($_GET['uzemanyag']) && $_GET['uzemanyag'] === 'Dízel') ? 'selected' : '' ?>>Dízel</option>
                    <option value="Elektromos" <?= (isset($_GET['uzemanyag']) && $_GET['uzemanyag'] === 'Elektromos') ? 'selected' : '' ?>>Elektromos</option>
                </select>
            </div>

            <div class="form-group">
                <label for="valto">Sebességváltó:</label>
                <select name="valto" id="valto">
                    <option value="">-- Sebességváltó --</option>
                    <option value="Manuális" <?= (isset($_GET['valto']) && $_GET['valto'] === 'Manuális') ? 'selected' : '' ?>>Manuális</option>
                    <option value="Automata" <?= (isset($_GET['valto']) && $_GET['valto'] === 'Automata') ? 'selected' : '' ?>>Automata</option>
                </select>
            </div>

            <div class="form-group">
                <label for="min_ar">Min. Ár:</label>
                <input type="number" name="min_ar" id="min_ar" value="<?= isset($_GET['min_ar']) ? htmlspecialchars($_GET['min_ar']) : '' ?>" />
            </div>

            <div class="form-group">
                <label for="max_ar">Max. Ár:</label>
                <input type="number" name="max_ar" id="max_ar" value="<?= isset($_GET['max_ar']) ? htmlspecialchars($_GET['max_ar']) : '' ?>" />
            </div>

            <div class="form-group">
                <label for="min_ev">Min. Év:</label>
                <input type="number" name="min_ev" id="min_ev" value="<?= isset($_GET['min_ev']) ? htmlspecialchars($_GET['min_ev']) : '' ?>" />
            </div>

            <div class="form-group">
                <label for="max_ev">Max. Év:</label>
                <input type="number" name="max_ev" id="max_ev" value="<?= isset($_GET['max_ev']) ? htmlspecialchars($_GET['max_ev']) : '' ?>" />
            </div>

            <input type="submit" value="Szűrés" />
        </form>
    </div>

    <?php if (!empty($autos)): ?>
    <?php foreach ($autos as $auto): ?>
        <div class="card">
            <h3><?= htmlspecialchars($auto['leiras']) ?></h3>
            <img src="uploads/<?= htmlspecialchars($auto['kép']) ?>" alt="<?= htmlspecialchars($auto['leiras']) ?>" />
            <p><strong>Ár:</strong> <?= htmlspecialchars($auto['vetelar']) ?> Ft</p>
            <p><strong>Évjárat:</strong> <?= htmlspecialchars($auto['evjarat']) ?></p>
            <p><strong>Üzemanyag:</strong> <?= htmlspecialchars($auto['uzemanyag']) ?></p>
            <p><strong>Sebességváltó:</strong> <?= htmlspecialchars($auto['sebessegvalto']) ?></p>
        </div>
    <?php endforeach; ?>
<?php else: ?>
<?php endif; ?>

    </div>
</body>
</html>

<?php
$conn->close(); // Adatbázis kapcsolat lezárása
?>
