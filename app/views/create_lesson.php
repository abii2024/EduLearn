<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class CreateLessonView {
    public static function Render($classes = [], $error = null) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>📝 Nieuwe Les Toevoegen</h1>

            <?php if (isset($error)) : ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (empty($classes)) : ?>
                <div class="warning">
                    <p>Je hebt nog geen klassen aangemaakt. Maak eerst een klas aan voordat je een les kunt toevoegen.</p>
                    <a href="/EduLearn/public/classes/create" class="btn">➕ Klas aanmaken</a>
                </div>
            <?php else : ?>
                <form action="/EduLearn/public/lessons/create" method="POST" class="form-box">
                    <label for="title">Lestitel:</label>
                    <input type="text" id="title" name="title" required placeholder="Bijv. Introductie HTML">

                    <label for="description">Beschrijving:</label>
                    <textarea id="description" name="description" rows="4" placeholder="Beschrijf wat studenten gaan leren in deze les..."></textarea>

                    <label for="class_id">Selecteer Klas:</label>
                    <select id="class_id" name="class_id" required>
                        <option value="">-- Kies een klas --</option>
                        <?php foreach ($classes as $class) : ?>
                            <option value="<?= $class['id'] ?? '' ?>"><?= htmlspecialchars($class['name'] ?? 'Onbekende klas') ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div class="form-actions">
                        <button type="submit" class="btn">✅ Les aanmaken</button>
                        <a href="/EduLearn/public/dashboard" class="btn btn-secondary">❌ Annuleren</a>
                    </div>
                </form>
            <?php endif; ?>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
?>
