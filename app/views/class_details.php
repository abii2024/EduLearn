<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class ClassDetailsView {
    public static function Render($class, $students = [], $availableStudents = []) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>👥 <?= htmlspecialchars($class['name'] ?? 'Onbekende klas') ?></h1>
            
            <div class="class-info">
                <p><strong>Beschrijving:</strong> <?= htmlspecialchars($class['description'] ?? 'Geen beschrijving beschikbaar.') ?></p>
                <p><small>Aangemaakt: <?= date('d-m-Y', strtotime($class['created_at'] ?? 'now')) ?></small></p>
            </div>

            <div class="class-management">
                <div class="enrolled-students">
                    <h2>📋 Ingeschreven Studenten (<?= count($students) ?>)</h2>
                    
                    <?php if (!empty($students) && is_array($students)) : ?>
                        <div class="students-list">
                            <?php foreach ($students as $student) : ?>
                                <div class="student-item">
                                    <div class="student-info">
                                        <strong><?= htmlspecialchars($student['name'] ?? 'Onbekende student') ?></strong>
                                        <small><?= htmlspecialchars($student['email'] ?? '') ?></small>
                                        <small>Ingeschreven: <?= date('d-m-Y', strtotime($student['enrolled_at'] ?? 'now')) ?></small>
                                    </div>
                                    <form method="POST" action="/EduLearn/public/classes/unenroll" class="inline-form">
                                        <input type="hidden" name="class_id" value="<?= $class['id'] ?? '' ?>">
                                        <input type="hidden" name="student_id" value="<?= $student['id'] ?? '' ?>">
                                        <button type="submit" class="btn btn-small btn-danger" onclick="return confirm('Weet je zeker dat je deze student wilt uitschrijven?')">❌ Uitschrijven</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p>Er zijn nog geen studenten ingeschreven voor deze klas.</p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($availableStudents) && is_array($availableStudents)) : ?>
                    <div class="add-students">
                        <h3>➕ Student Toevoegen</h3>
                        <form method="POST" action="/EduLearn/public/classes/enroll" class="form-inline">
                            <input type="hidden" name="class_id" value="<?= $class['id'] ?? '' ?>">
                            <select name="student_id" required>
                                <option value="">-- Selecteer een student --</option>
                                <?php foreach ($availableStudents as $student) : ?>
                                    <option value="<?= $student['id'] ?? '' ?>">
                                        <?= htmlspecialchars($student['name'] ?? 'Onbekende student') ?> 
                                        (<?= htmlspecialchars($student['email'] ?? '') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-small">✅ Inschrijven</button>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="no-available-students">
                        <p>Alle studenten zijn al ingeschreven voor deze klas.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="class-actions">
                <a href="/EduLearn/public/classes" class="btn btn-secondary">⬅️ Terug naar klassen</a>
                <a href="/EduLearn/public/lessons/create?class=<?= $class['id'] ?? '#' ?>" class="btn">📝 Les toevoegen</a>
            </div>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
?>
