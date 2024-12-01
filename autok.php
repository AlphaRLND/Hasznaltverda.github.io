<?php

// Adatbázis-kapcsolat betöltése
require_once 'kapcsolat.php';

// Lekérdezés az összes autó adatainak lekéréséhez
$sql = "SELECT id, vetelar, ar_eur, evjarat, allapot, futott_km, szin, leiras FROM autok";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autók listája</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 80%;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .card h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #333;
        }

        .card p {
            margin: 5px 0;
            font-size: 0.9em;
            color: #555;
        }

        .action-links {
            margin-top: 10px;
        }

        .action-links a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Autók listája</h1>

    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($adatok = $result->fetch_assoc()): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($adatok['leiras']) ?></h2>
                    <p>Vételár: <?= htmlspecialchars($adatok['vetelar']) ?> Ft (<?= htmlspecialchars($adatok['ar_eur']) ?> €)</p>
                    <p>Állapot: <?= htmlspecialchars($adatok['allapot']) ?></p>
                    <p>Futott km: <?= htmlspecialchars($adatok['futott_km']) ?> km</p>
                    <p>Szín: <?= htmlspecialchars($adatok['szin']) ?></p>
                    
                    <div class="action-links">
                        <a href="edit.php?id=<?= htmlspecialchars($adatok['id']) ?>">Módosítás</a>
                        <a href="delete.php?id=<?= htmlspecialchars($adatok['id']) ?>">Törlés</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nincs autó az adatbázisban.</p>
        <?php endif; ?>
    </div>

    <?php
    // Kapcsolat lezárása
    $conn->close();
    ?>
</body>
</html>
