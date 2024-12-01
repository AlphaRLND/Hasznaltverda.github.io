<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="wrapper show-signup"> <!-- Alapértelmezetten a Sign Up form jelenik meg -->
        <div class="form-container">
            <!-- Login Form -->
            <div class="form-box login">
            <h2>Belépés</h2>
                <form action="register_login.php" method="POST">
                    <div class="input-box">
                        <span class="icon">👤</span>
                        <input type="text" name="username" required>
                        <label>Felhasználónév</label>
                    </div>
                    <div class="input-box">
                        <span class="icon">🔒</span>
                        <input type="password" name="password" required>
                        <label>Jelszó</label>
                    </div>
                    <button type="submit">Belépés</button>
                    <div class="register-link">
                        <p>Nincs még fiókod? <a href="#" id="show-signup">Regisztráció</a></p>
                    </div>
                </form>
            </div>

            <!-- Info Text (Black Section) -->
            <div class="info-text"></div>

            <!-- Sign Up Form (Initially hidden) -->
            <div class="form-box signup">
                <h2>Regisztráció</h2>
                <form action="register_process.php" method="POST">
                    <div class="input-box">
                        <span class="icon">👤</span>
                        <input type="text" name="username" required>
                        <label>Felhasználónév</label>
                    </div>
                    <div class="input-box">
                        <span class="icon">🔒</span>
                        <input type="password" name="password" required>
                        <label>Jelszó</label>
                    </div>
                    <button type="submit">Regisztráció</button>
                    <div class="login-link">
                        <p>Van már fiókod? <a href="#" id="show-login">Belépés</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle between login and sign up
        document.getElementById('show-signup').addEventListener('click', function() {
            document.querySelector('.wrapper').classList.add('show-signup');
        });
        document.getElementById('show-login').addEventListener('click', function() {
            document.querySelector('.wrapper').classList.remove('show-signup');
        });
    </script>
</body>
</html>
