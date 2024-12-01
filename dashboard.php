<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ellenőrizzük, hogy a session elindult-e
}

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Ellenőrizzük, hogy a kiválasztott műveletet elküldték-e
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'upload') {
        header('Location: urlap.php'); // Új autó feltöltése
        exit;
    } elseif ($action === 'edit' || $action === 'delete') {
        header('Location: autok.php'); // Autó módosítása vagy törlése
        exit;
    } elseif ($action === 'view') {
        header('Location: index.html'); // Autók megtekintése
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vezérlőpult</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Üdvözöljük!</h1>
    
    <form action="" method="post">
        <label for="action">Válasszon egy műveletet:</label>
        <select name="action" id="action">
            <option value="">-- Válasszon --</option>
            <option value="upload">Új autó feltöltése</option>
            <option value="edit">Autó módosítása</option>
            <option value="delete">Autó törlése</option>
            <option value="view">Autók megtekintése</option> <!-- Új megtekintés opció -->
        </select>
        <input type="submit" value="Kiválasztás">
    </form>
</body>
</html>
