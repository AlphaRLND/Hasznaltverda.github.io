<?php
// Az autoload fájl betöltése
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ha a kérelem POST metódussal érkezett
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Űrlap adatainak begyűjtése
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    
    try {
        // PHPMailer példány létrehozása
        $mail = new PHPMailer(true);

        // Szerver beállítások
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // SMTP szerver címe
        $mail->SMTPAuth = true;
        $mail->Username = 'your@example.com'; // SMTP felhasználónév
        $mail->Password = 'yourpassword'; // SMTP jelszó
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // SMTP port
        
        // Feladó és címzett beállítása
        $mail->setFrom('your@example.com', 'Your Name');
        $mail->addAddress('horvathroland007@gmail.com'); // Címzett e-mail címe
        
        // E-mail tartalma
        $mail->isHTML(false);
        $mail->Subject = 'Új bejelentkezés';
        $mail->Body = "Felhasználónév: $username\nEmail cím: $email\nTelefonszám: $phone\nKívánt szolgáltatás: $service";

        // E-mail küldése
        $mail->send();
        echo 'Az e-mail sikeresen elküldve.';
    } catch (Exception $e) {
        echo "Hiba történt az e-mail küldése közben: {$mail->ErrorInfo}";
    }
}
?>
