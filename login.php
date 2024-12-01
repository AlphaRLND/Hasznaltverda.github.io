<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ellenőrizzük, hogy a session elindult-e
}

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: dashboard.php'); // Ha már be van jelentkezve, irányítsuk a vezérlőpultra
    exit;
}

// Kezeljük a bejelentkezési form adatait
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $felhasznalonev = $_POST['felhasznalonev'];
    $jelszo = $_POST['jelszo'];

    // Egyszerű felhasználónév-jelszó páros (később adatbázisból is lehetne)
    $helyes_felhasznalonev = 'admin';
    $helyes_jelszo = 'jelszo123';

    if ($felhasznalonev === $helyes_felhasznalonev && $jelszo === $helyes_jelszo) {
        $_SESSION['logged_in'] = true; // Bejelentkezett állapot
        header('Location: dashboard.php'); // Irányítás a vezérlőpultra
        exit;
    } else {
        $hiba = "Helytelen felhasználónév vagy jelszó!";
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #E0E0E0;
            padding: 20px;
            text-align: center;
        }

        form {
            background-color: #181818;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #333;
            border-radius: 5px;
            background-color: #1E1E1E;
            color: #E0E0E0;
        }

        input[type="submit"] {
            background-color: #1DB954;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #1AAE45;
        }

        .error {
            color: #FF6666;
        }
    </style>
</head>
<body>
    <h1>Bejelentkezés</h1>
    
    <form action="login.php" method="post">
        <label for="felhasznalonev">Felhasználónév:</label><br>
        <input type="text" id="felhasznalonev" name="felhasznalonev" required><br>
        
        <label for="jelszo">Jelszó:</label><br>
        <input type="password" id="jelszo" name="jelszo" required><br>
        
        <input type="submit" value="Bejelentkezés"><br>
    </form>

    <?php if (isset($hiba)): ?>
        <p class="error"><?= htmlspecialchars($hiba) ?></p>
    <?php endif; ?>
</body>
</html>
