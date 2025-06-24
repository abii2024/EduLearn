<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class CreateClassView {
    public static function Render($error = null) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>➕ Nieuwe Klas Aanmaken</h1>

            <?php if (isset($error)) : ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="/EduLearn/public/classes/create" method="POST" class="form-box">
                <label for="name">Klasnaam:</label>
                <input type="text" id="name" name="name" required placeholder="Bijv. Webontwikkeling Basis">

                <label for="description">Beschrijving:</label>
                <textarea id="description" name="description" rows="4" placeholder="Beschrijf waar deze klas over gaat..."></textarea>

                <div class="form-actions">
                    <button type="submit" class="btn">✅ Klas aanmaken</button>
                    <a href="/EduLearn/public/classes" class="btn btn-secondary">❌ Annuleren</a>
                </div>
            </form>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
?>
