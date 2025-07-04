<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class LoginView {
    public static function Render($error = null) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>🔐 Inloggen</h1>

            <?php if (isset($error)) : ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="/EduLearn/public/login" method="POST" class="form-box">
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn">Inloggen</button>
            </form>

            <p>Nog geen account? <a href="/EduLearn/public/register">Registreer hier</a>.</p>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
