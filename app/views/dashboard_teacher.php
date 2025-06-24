<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class DashboardTeacherView {
    public static function Render($teacherName, $lessons = [], $recentAssignments = []) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>👨‍🏫 Welkom, <?= htmlspecialchars($teacherName ?? 'Docent') ?></h1>

            <section>
                <h2>📚 Mijn Lessen</h2>
                <?php if (!empty($lessons) && is_array($lessons)) : ?>
                    <ul class="lesson-list">
                        <?php foreach ($lessons as $lesson) : ?>
                            <li>
                                <strong><?= htmlspecialchars($lesson['title'] ?? 'Onbekende les') ?></strong><br>
                                <small><?= htmlspecialchars($lesson['description'] ?? 'Geen beschrijving beschikbaar.') ?></small><br>
                                <a href="/EduLearn/public/lessons/<?= $lesson['id'] ?? '#' ?>/assignments">📂 Bekijk opdrachten</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Je hebt nog geen lessen toegevoegd.</p>
                <?php endif; ?>

                <div class="teacher-actions">
                    <a href="/EduLearn/public/classes" class="btn">📚 Beheer klassen</a>
                    <a href="/EduLearn/public/lessons/create" class="btn">➕ Nieuwe les toevoegen</a>
                </div>
            </section>

            <section>
                <h2>📝 Laatste opdrachten</h2>
                <?php if (!empty($recentAssignments) && is_array($recentAssignments)) : ?>
                    <ul class="assignment-list">
                        <?php foreach ($recentAssignments as $assignment) : ?>
                            <li>
                                <strong><?= htmlspecialchars($assignment['title'] ?? 'Onbekende opdracht') ?></strong><br>
                                Deadline: <?= $assignment['deadline'] ? date('d-m-Y H:i', strtotime($assignment['deadline'])) : 'Geen deadline' ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Er zijn nog geen opdrachten beschikbaar.</p>
                <?php endif; ?>
            </section>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
