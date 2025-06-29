<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

include_once __DIR__ . "/shared/header.php";
include_once __DIR__ . "/shared/navbar.php";
?>

<main class="container">
    <h1>👥 Docent Admin - Studenten Beheren</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Create New Student Section -->
    <div class="admin-section">
        <h2>➕ Nieuwe Student Aanmaken</h2>
        <form method="POST" action="/EduLearn/public/teacher-admin/create-student" class="form-box">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Volledige naam:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mailadres:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Tijdelijk wachtwoord:</label>
                <input type="password" id="password" name="password" required minlength="6">
                <small>Minimaal 6 karakters. Student kan dit later wijzigen.</small>
            </div>
            <button type="submit" class="btn btn-primary">Student Aanmaken</button>
        </form>
    </div>

    <!-- Students Management Section -->
    <div class="admin-section">
        <h2>📋 Alle Studenten</h2>
        <div class="students-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naam</th>
                        <th>Email</th>
                        <th>Klassen</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['id']) ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['email']) ?></td>
                        <td>
                            <?php
                            // Get classes for this student
                            require_once __DIR__ . '/../models/ClassModel.php';
                            $studentClasses = ClassModel::getClassesForStudent($student['id']);
                            $teacherClasses = array_filter($studentClasses, function($class) {
                                return $class['teacher_id'] == $_SESSION['user']['id'];
                            });
                            ?>
                            <?php if (empty($teacherClasses)): ?>
                                <span class="no-classes">Geen klassen</span>
                            <?php else: ?>
                                <?php foreach ($teacherClasses as $class): ?>
                                    <span class="class-badge"><?= htmlspecialchars($class['name']) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <!-- Enroll in Class -->
                            <details class="action-dropdown">
                                <summary class="btn-small btn-info">📚 Beheer Klassen</summary>
                                <div class="dropdown-content">
                                    <?php if (!empty($teacherClasses)): ?>
                                        <h4>Uitschrijven uit:</h4>
                                        <?php foreach ($teacherClasses as $class): ?>
                                            <form method="POST" action="/EduLearn/public/teacher-admin/remove-from-class" style="display: inline;">
                                                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                                <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
                                                <button type="submit" class="btn-small btn-warning" onclick="return confirm('Student uitschrijven uit <?= htmlspecialchars($class['name']) ?>?')">
                                                    ❌ <?= htmlspecialchars($class['name']) ?>
                                                </button>
                                            </form>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <?php
                                    // Get available classes (teacher's classes where student is not enrolled)
                                    $availableClasses = array_filter($teacherClasses, function($teacherClass) use ($teacherClasses) {
                                        foreach ($teacherClasses as $studentClass) {
                                            if ($teacherClass['id'] == $studentClass['id']) {
                                                return false;
                                            }
                                        }
                                        return true;
                                    });
                                    
                                    // Get all teacher classes and filter out where student is already enrolled
                                    $allTeacherClasses = $teacherClasses; // This is wrong, let me fix this
                                    ?>
                                    <h4>Inschrijven in:</h4>
                                    <?php 
                                    // Get all teacher's classes and show ones where student is NOT enrolled
                                    $enrolledClassIds = array_column($teacherClasses, 'id');
                                    ?>
                                    
                                    <?php foreach ($teacherClasses as $class): ?>
                                        <?php if (!in_array($class['id'], $enrolledClassIds)): ?>
                                            <form method="POST" action="/EduLearn/public/teacher-admin/enroll-in-class" style="display: inline;">
                                                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                                <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
                                                <button type="submit" class="btn-small btn-success">
                                                    ✅ <?= htmlspecialchars($class['name']) ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </details>
                            
                            <!-- Reset Password -->
                            <details class="action-dropdown">
                                <summary class="btn-small btn-warning">🔑 Reset Wachtwoord</summary>
                                <div class="dropdown-content">
                                    <form method="POST" action="/EduLearn/public/teacher-admin/reset-password">
                                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                                        <label for="new_password_<?= $student['id'] ?>">Nieuw wachtwoord:</label>
                                        <input type="password" id="new_password_<?= $student['id'] ?>" name="new_password" required minlength="6">
                                        <button type="submit" class="btn-small btn-warning" onclick="return confirm('Wachtwoord resetten voor <?= htmlspecialchars($student['name']) ?>?')">
                                            Reset Wachtwoord
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Class Enrollment Section -->
    <?php if (!empty($teacherClasses)): ?>
    <div class="admin-section">
        <h2>📚 Snelle Inschrijving</h2>
        <p>Schrijf studenten snel in voor je klassen:</p>
        <form method="POST" action="/EduLearn/public/teacher-admin/enroll-in-class" class="quick-enroll-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="quick_student">Selecteer Student:</label>
                    <select id="quick_student" name="student_id" required>
                        <option value="">-- Kies een student --</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?> (<?= htmlspecialchars($student['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quick_class">Selecteer Klas:</label>
                    <select id="quick_class" name="class_id" required>
                        <option value="">-- Kies een klas --</option>
                        <?php foreach ($teacherClasses as $class): ?>
                            <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Student Inschrijven</button>
        </form>
    </div>
    <?php endif; ?>
</main>

<style>
.admin-section {
    background: white;
    margin: 20px 0;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.form-group {
    flex: 1;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input, .form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-group small {
    color: #666;
    font-size: 0.9em;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f8f9fa;
    font-weight: bold;
}

tr:hover {
    background-color: #f8f9fa;
}

.class-badge {
    display: inline-block;
    background: #e3f2fd;
    color: #1976d2;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.85em;
    margin: 2px;
}

.no-classes {
    color: #999;
    font-style: italic;
}

.actions {
    white-space: nowrap;
}

.action-dropdown {
    display: inline-block;
    position: relative;
    margin: 2px;
}

.action-dropdown summary {
    cursor: pointer;
    list-style: none;
}

.dropdown-content {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 100;
    min-width: 200px;
}

.dropdown-content h4 {
    margin: 0 0 8px 0;
    font-size: 0.9em;
    color: #666;
}

.btn-small {
    padding: 6px 12px;
    font-size: 0.85em;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin: 2px;
    text-decoration: none;
    display: inline-block;
}

.btn-primary { background: #007bff; color: white; }
.btn-success { background: #28a745; color: white; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-info { background: #17a2b8; color: white; }

.btn-primary:hover { background: #0056b3; }
.btn-success:hover { background: #218838; }
.btn-warning:hover { background: #e0a800; }
.btn-info:hover { background: #138496; }

.success {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #c3e6cb;
    margin-bottom: 20px;
}

.error {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #f5c6cb;
    margin-bottom: 20px;
}

.quick-enroll-form {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
}
</style>

<?php include_once __DIR__ . "/shared/footer.php"; ?>
