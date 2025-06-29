<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class RegisterView {
    public static function Render($error = null) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>📝 Registreren</h1>

            <?php if (isset($error)) : ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="/EduLearn/public/register" method="POST" class="form-box">
                <label for="name">Volledige naam:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>

                <label for="role">Rol:</label>
                <select id="role" name="role" required>
                    <option value="student">Student</option>
                    <option value="teacher">Docent</option>
                    <option value="admin">Administrator</option>
                </select>

                <button type="submit" class="btn">Account aanmaken</button>
            </form>

            <p>Heb je al een account? <a href="/EduLearn/public/login">Log hier in</a>.</p>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
