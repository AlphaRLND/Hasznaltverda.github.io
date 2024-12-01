<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scroll Animated Website</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style_2.css">
    <link rel="stylesheet" href="style_3.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

</head>
<body>

    <!-- Navigációs sáv -->
    <nav>
        <div class="menu-toggle" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="nav-links">
            <li><a href="#section1">Kezdőlap</a></li>
            <li><a href="#section2">Kínálat</a></li>
            <li><a href="#section3">Rólunk</a></li>
            <li><a href="#section4">Kapcsolat</a></li>
        </ul>
        <div class="nav-right">
            <ul>
            <li><a href="register.php">Regisztráció</a></li>
            <li><a href="register_login.php">Belépés</a></li>
                <li>
                    <a href="#garage" class="garage-link">
                        <img src="garage.png" alt="Garázs" class="garage-icon">
                    </a>
                </li>
            </ul>
        </div>
        <!-- Az animált csík -->
        <span class="nav-indicator"></span>
    </nav>

    <section class="video-section" id="section1">
        <video autoplay muted loop playsinline class="background-video">
            <source src="start.mp4" type="video/mp4">
            Az ön böngészője nem támogatja a videólejátszást.
        </video>s
        <div class="video-overlay">
            <!-- Tartalom a videó fölött -->
            <div class="container">
                <h1 class="animated-text">
                    <span class="letter">H</span>
                    <span class="letter">a</span>
                    <span class="letter">s</span>
                    <span class="letter">z</span>
                    <span class="letter">n</span>
                    <span class="letter">á</span>
                    <span class="letter">l</span>
                    <span class="letter">t</span>
                    <span class="letter">v</span>
                    <span class="letter">e</span>
                    <span class="letter">r</span>
                    <span class="letter">d</span>
                    <span class="letter">a</span>
                </h1>
            </div>
        </div>
    </section>
    <h1>Autókereső</h1>
    <?php include 'search_form.php'; ?> <!-- Szűrési funkció betöltése -->
    <?php

    // A megjelenit.php fájl beillesztése
    include 'megjelenit.php';
    ?>
    <section class="content-section white-section" id="section2">
        <div class="scroll-item" data-animation="slide-in-left">I slide in from the left!</div>
    </section>

    <section class="content-section gray-section" id="section3">
        <div class="card-container">
            <div class="card" data-animation="fade-in">
                <img src="used_car.png" alt="Kép 1" class="card-image">
                <div class="card-content">
                    <h3>Legjobb Használt Autók</h3>
                    <p>Fedezd fel a legjobb használt autóinkat, amelyek tökéletes kombinációt nyújtanak a minőségből és a megfizethetőséggel. Minden autót alaposan átvizsgálunk, hogy biztosítsuk a megbízhatóságot és a biztonságot. Válaszd ki álmaid autóját még ma!</p>
                </div>
            </div>
            <div class="card" data-animation="fade-in">
                <img src="couple.jpg" alt="Kép 2" class="card-image">
                <div class="card-content">
                    <h3>Finanszírozási Lehetőségek</h3>
                    <p>Ne hagyd, hogy a költségek megakadályozzanak abban, hogy megszerezd álmaid autóját! Kínálunk rugalmas finanszírozási lehetőségeket, amelyek segítenek, hogy a legjobban illeszkedjenek a költségvetésedhez. Kérd személyre szabott ajánlatunkat még ma!</p>
                </div>
            </div>
            <div class="card" data-animation="fade-in">
                <img src="specialist.jpg" alt="Kép 3" class="card-image">
                <div class="card-content">
                    <h3>Autóvásárlási Útmutató</h3>
                    <p>Az autóvásárlás sok kérdést vethet fel. Segítünk, hogy a legjobb döntést hozd! Fedezd fel hasznos tanácsainkat a vásárlási folyamat során, mint például, hogyan válassz megfelelő modellt, mire figyelj a használt autók állapotánál, és hogyan találj jó ár-érték arányt!</p>
                </div>
            </div>
        </div>
    </section>
    

    <section class="content-section white-section" id="section4">
        <div class="scroll-item" data-animation="fade-in">I fade in again!</div>
    </section>

    <footer>
        <p>End of the page</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('nav ul li a');
    const indicator = document.querySelector('.nav-indicator');
    let activeLink = navLinks[0]; // Kezdésként az első menüelem legyen aktív

    // Navigációs csík mozgatása
    function updateIndicator(link) {
        const linkCoords = link.getBoundingClientRect();
        const navCoords = link.closest('nav').getBoundingClientRect();

        indicator.style.width = `${linkCoords.width}px`;
        indicator.style.transform = `translateX(${linkCoords.left - navCoords.left}px)`;
    }

    navLinks.forEach(link => {
        // Kattintáskor csík mozgatása és a tartalom görgetése
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');

            // Ha a link egy belső hivatkozás (#), akkor lekezeljük a smooth scroll-t
            if (href.startsWith('#')) {
                e.preventDefault(); // Ne frissítse az oldalt
                activeLink = this; // Aktív elem frissítése
                updateIndicator(this);
                document.querySelector(href).scrollIntoView({ behavior: 'smooth' });
            }
            // Ha a link külső (nem #), akkor engedjük a normál navigálást
        });

        // Egérrel történő rámutatás esetén csík mozgatása
        link.addEventListener('mouseenter', function () {
            updateIndicator(this);
        });

        // Egérrel történő elhagyáskor visszaállítás az aktív elemhez
        link.addEventListener('mouseleave', function () {
            updateIndicator(activeLink);
        });
    });

    // Első elem kiválasztásával kezdd
    updateIndicator(activeLink);

    // Az Intersection Observer beállítása az animációk újrajátszásához
    const scrollItems = document.querySelectorAll('.scroll-item');
    const cards = document.querySelectorAll('.card'); // Kártyák kiválasztása

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Késleltetés beállítása a kártyákhoz
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 300); // Kártyák 300 ms késleltetéssel
            } else {
                // Eltávolítjuk a "visible" osztályt, hogy újrainduljon az animáció, amikor visszatér
                entry.target.classList.remove('visible');
            }
        });
    }, {
        threshold: 0.1 // Az elem 10%-ban látható kell legyen a triggerhez
    });

    // Minden animációs elem megfigyelése
    scrollItems.forEach(item => {
        observer.observe(item);
    });

    // Kártyák figyelése is
    cards.forEach(card => {
        observer.observe(card);
    });
});



window.addEventListener('scroll', () => {
    const scrollItems = document.querySelectorAll('.scroll-item');

    scrollItems.forEach(item => {
        const itemPosition = item.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        // When the item is in view, add the 'visible' class
        if (itemPosition < windowHeight - 150) {
            item.classList.add('visible');
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const car = document.getElementById("car");
    const letters = document.querySelectorAll(".letter");

    // Az autó mozgásának ideje (4 másodperc) után kezdődik a betűk megjelenése
    const carAnimationDuration = 3000; // 3 másodperc az autó animációjának hossza
    let delay = 500; // Kezdeti késleltetés az első betű előtt (0.5 másodperc)

    // Várakozás az autó animáció végéig, majd a betűk megjelenítése
    setTimeout(() => {
        letters.forEach((letter, index) => {
            setTimeout(() => {
                letter.classList.add("show");
            }, delay);
            delay += 100; // 0.1 másodperc késleltetés minden betű között
        });
    }, carAnimationDuration); // Az autó animáció idejével szinkronizálva
});

// script.js

// script.js
function toggleMenu() {
    const navLinks = document.querySelector('.nav-links');
    const navRight = document.querySelector('.nav-right');
    navLinks.classList.toggle('show');
    navRight.classList.toggle('show');
}

    </script>
</body>
</html>
