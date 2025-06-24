<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class ClassesListView {
    public static function Render($classes = []) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>📚 Mijn Klassen</h1>
            
            <div class="action-buttons">
                <a href="/EduLearn/public/classes/create" class="btn">➕ Nieuwe klas aanmaken</a>
            </div>

            <?php if (!empty($classes) && is_array($classes)) : ?>
                <div class="classes-grid">
                    <?php foreach ($classes as $class) : ?>
                        <div class="class-card">
                            <h3><?= htmlspecialchars($class['name'] ?? 'Onbekende klas') ?></h3>
                            <p><?= htmlspecialchars($class['description'] ?? 'Geen beschrijving beschikbaar.') ?></p>
                            <div class="class-meta">
                                <small>Aangemaakt: <?= date('d-m-Y', strtotime($class['created_at'] ?? 'now')) ?></small>
                            </div>
                            <div class="class-actions">
                                <a href="/EduLearn/public/classes/<?= $class['id'] ?? '#' ?>" class="btn btn-small">👥 Studenten beheren</a>
                                <a href="/EduLearn/public/lessons/create?class=<?= $class['id'] ?? '#' ?>" class="btn btn-small">📝 Les toevoegen</a>
                                <form method="POST" action="/EduLearn/public/classes/delete" class="inline-form">
                                    <input type="hidden" name="class_id" value="<?= $class['id'] ?? '' ?>">
                                    <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Weet je zeker dat je deze klas wilt verwijderen? Dit kan niet ongedaan worden gemaakt en alle inschrijvingen worden ook verwijderd.')">🗑️ Verwijderen</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="empty-state">
                    <p>Je hebt nog geen klassen aangemaakt.</p>
                    <a href="/EduLearn/public/classes/create" class="btn">Maak je eerste klas aan</a>
                </div>
            <?php endif; ?>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
?>
