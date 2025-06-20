<?php

class DashboardStudentView {
    public static function Render($studentName, $lessons = [], $assignments = []) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>🎓 Welkom, <?= htmlspecialchars($studentName ?? 'Student') ?></h1>

            <section>
                <h2>📚 Mijn lessen</h2>
                <?php if (!empty($lessons)) : ?>
                    <ul class="lesson-list">
                        <?php foreach ($lessons as $lesson) : ?>
                            <li>
                                <strong><?= htmlspecialchars($lesson['title']) ?></strong><br>
                                <small><?= htmlspecialchars($lesson['description']) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Je bent nog niet ingeschreven voor lessen.</p>
                <?php endif; ?>
            </section>

            <section>
                <h2>📝 Opdrachten</h2>
                <?php if (!empty($assignments)) : ?>
                    <ul class="assignment-list">
                        <?php foreach ($assignments as $assignment) : ?>
                            <li>
                                <strong><?= htmlspecialchars($assignment['title']) ?></strong><br>
                                Deadline: <?= date('d-m-Y H:i', strtotime($assignment['deadline'])) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Geen opdrachten gevonden.</p>
                <?php endif; ?>
            </section>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
