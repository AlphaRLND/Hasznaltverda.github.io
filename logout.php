<?php
session_start();
// Munkamenet lezárása (minden munkamenet változó törlése)
$_SESSION = array();

// Ha a session cookie be van állítva, akkor töröljük azt is
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Munkamenet befejezése
session_destroy();

// Visszairányítás a bejelentkezési oldalra
header('Location: login.php');
exit;
?>

