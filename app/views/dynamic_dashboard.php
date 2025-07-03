<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class DynamicDashboardView {
    
    /**
     * Hoofdmethode die de juiste view kiest op basis van gebruiker
     */
    public static function Render($data = []) {
        // Controleer of gebruiker is ingelogd
        if (!isset($_SESSION['user'])) {
            self::RenderNotLoggedIn();
            return;
        }
        
        $userRole = $_SESSION['user']['role'];
        $userName = $_SESSION['user']['name'];
        
        // Kies de juiste view op basis van rol
        switch ($userRole) {
            case 'student':
                self::RenderStudentDashboard($data, $userName);
                break;
            case 'teacher':
                self::RenderTeacherDashboard($data, $userName);
                break;
            case 'admin':
                self::RenderAdminDashboard($data, $userName);
                break;
            default:
                self::RenderDefaultDashboard($data, $userName);
        }
    }
    
    /**
     * View voor niet-ingelogde gebruikers
     */
    private static function RenderNotLoggedIn() {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <div class="access-denied">
                <h1>🔐 Toegang Geweigerd</h1>
                <p>Je moet ingelogd zijn om toegang te krijgen tot het dashboard.</p>
                
                <div class="login-options">
                    <h2>Kies je optie:</h2>
                    <div class="option-cards">
                        <div class="option-card">
                            <h3>🎓 Student</h3>
                            <p>Toegang tot je cursussen, opdrachten en voortgang</p>
                            <a href="/EduLearn/public/login" class="btn">Inloggen als Student</a>
                        </div>
                        <div class="option-card">
                            <h3>👨‍🏫 Docent</h3>
                            <p>Beheer je klassen, lessen en studenten</p>
                            <a href="/EduLearn/public/login" class="btn">Inloggen als Docent</a>
                        </div>
                        <div class="option-card">
                            <h3>🔧 Administrator</h3>
                            <p>Volledige toegang tot systeembeheer</p>
                            <a href="/EduLearn/public/login" class="btn">Inloggen als Admin</a>
                        </div>
                    </div>
                </div>
                
                <div class="register-prompt">
                    <h3>Nog geen account?</h3>
                    <a href="/EduLearn/public/register" class="btn btn-secondary">Registreren</a>
                </div>
            </div>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
    
    /**
     * Student Dashboard - Focus op leren en voortgang
     */
    private static function RenderStudentDashboard($data, $userName) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>🎓 Student Dashboard - Welkom <?= htmlspecialchars($userName) ?>!</h1>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>📚 Mijn Cursussen</h3>
                    <p class="stat-number"><?= count($data['classes'] ?? []) ?></p>
                    <p>Actieve inschrijvingen</p>
                </div>
                <div class="stat-card">
                    <h3>📝 Opdrachten</h3>
                    <p class="stat-number"><?= count($data['assignments'] ?? []) ?></p>
                    <p>Te voltooien</p>
                </div>
                <div class="stat-card">
                    <h3>🎯 Voortgang</h3>
                    <p class="stat-number">85%</p>
                    <p>Gemiddelde score</p>
                </div>
            </div>
            
            <!-- Student specifieke content -->
            <section class="student-content">
                <h2>📋 Mijn Activiteiten</h2>
                
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>📚 Recente Lessen</h3>
                        <?php if (!empty($data['lessons'])): ?>
                            <ul>
                                <?php foreach (array_slice($data['lessons'], 0, 5) as $lesson): ?>
                                    <li>
                                        <strong><?= htmlspecialchars($lesson['title']) ?></strong>
                                        <br><small><?= htmlspecialchars($lesson['class_name']) ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Nog geen lessen beschikbaar.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="activity-card">
                        <h3>📝 Aankomende Deadlines</h3>
                        <?php if (!empty($data['assignments'])): ?>
                            <ul>
                                <?php foreach (array_slice($data['assignments'], 0, 5) as $assignment): ?>
                                    <li>
                                        <strong><?= htmlspecialchars($assignment['title']) ?></strong>
                                        <br><small>📅 <?= date('d-m-Y', strtotime($assignment['deadline'])) ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Geen aankomende deadlines.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
            <section class="student-actions">
                <h2>🚀 Snelle Acties</h2>
                <div class="action-buttons">
                    <a href="/EduLearn/public/classes" class="btn">📚 Mijn Cursussen</a>
                    <a href="/EduLearn/public/assignments" class="btn">📝 Opdrachten</a>
                    <a href="/EduLearn/public/grades" class="btn">🎯 Cijfers</a>
                </div>
            </section>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
    
    /**
     * Teacher Dashboard - Focus op klasbeheer
     */
    private static function RenderTeacherDashboard($data, $userName) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>👨‍🏫 Docent Dashboard - Welkom <?= htmlspecialchars($userName) ?>!</h1>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>🎓 Mijn Klassen</h3>
                    <p class="stat-number"><?= count($data['classes'] ?? []) ?></p>
                    <p>Actieve klassen</p>
                </div>
                <div class="stat-card">
                    <h3>👥 Studenten</h3>
                    <p class="stat-number"><?= $data['total_students'] ?? 0 ?></p>
                    <p>Totaal onder beheer</p>
                </div>
                <div class="stat-card">
                    <h3>📚 Lessen</h3>
                    <p class="stat-number"><?= count($data['lessons'] ?? []) ?></p>
                    <p>Gepubliceerde lessen</p>
                </div>
            </div>
            
            <!-- Teacher specifieke content -->
            <section class="teacher-content">
                <h2>🎯 Docent Activiteiten</h2>
                
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>🎓 Mijn Klassen</h3>
                        <?php if (!empty($data['classes'])): ?>
                            <ul>
                                <?php foreach ($data['classes'] as $class): ?>
                                    <li>
                                        <strong><?= htmlspecialchars($class['name']) ?></strong>
                                        <br><small><?= htmlspecialchars($class['description']) ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Nog geen klassen aangemaakt.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="activity-card">
                        <h3>📝 Recente Lessen</h3>
                        <?php if (!empty($data['lessons'])): ?>
                            <ul>
                                <?php foreach (array_slice($data['lessons'], 0, 5) as $lesson): ?>
                                    <li>
                                        <strong><?= htmlspecialchars($lesson['title']) ?></strong>
                                        <br><small><?= htmlspecialchars($lesson['class_name'] ?? 'Geen klas') ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>Nog geen lessen gemaakt.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            
            <section class="teacher-actions">
                <h2>🛠️ Beheer Functies</h2>
                <div class="action-buttons">
                    <a href="/EduLearn/public/create-class" class="btn">➕ Nieuwe Klas</a>
                    <a href="/EduLearn/public/create-lesson" class="btn">📝 Nieuwe Les</a>
                    <a href="/EduLearn/public/teacher-admin" class="btn">👥 Studenten Beheren</a>
                    <a href="/EduLearn/public/grades" class="btn">🎯 Cijfers Beheren</a>
                </div>
            </section>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
    
    /**
     * Admin Dashboard - Focus op systeembeheer
     */
    private static function RenderAdminDashboard($data, $userName) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>🔧 Administrator Dashboard - Welkom <?= htmlspecialchars($userName) ?>!</h1>
            
            <div class="dashboard-stats">
                <div class="stat-card">
                    <h3>👥 Totaal Gebruikers</h3>
                    <p class="stat-number"><?= $data['total_users'] ?? 0 ?></p>
                    <p>Systeem gebruikers</p>
                </div>
                <div class="stat-card">
                    <h3>🎓 Totaal Klassen</h3>
                    <p class="stat-number"><?= count($data['classes'] ?? []) ?></p>
                    <p>Actieve klassen</p>
                </div>
                <div class="stat-card">
                    <h3>📊 Systeem Status</h3>
                    <p class="stat-number">✅</p>
                    <p>Operationeel</p>
                </div>
            </div>
            
            <!-- Admin specifieke content -->
            <section class="admin-content">
                <h2>🛠️ Systeem Beheer</h2>
                
                <div class="admin-panels">
                    <div class="admin-panel">
                        <h3>👥 Gebruikers Beheer</h3>
                        <p>Beheer alle studenten, docenten en administrators</p>
                        <a href="/EduLearn/public/admin/users" class="btn">Gebruikers Beheren</a>
                    </div>
                    
                    <div class="admin-panel">
                        <h3>🎓 Klassen Overzicht</h3>
                        <p>Bekijk en beheer alle klassen in het systeem</p>
                        <a href="/EduLearn/public/admin/classes" class="btn">Klassen Beheren</a>
                    </div>
                    
                    <div class="admin-panel">
                        <h3>📊 Systeem Statistieken</h3>
                        <p>Detailleerde rapporten en analytics</p>
                        <a href="/EduLearn/public/admin/stats" class="btn">Statistieken Bekijken</a>
                    </div>
                    
                    <div class="admin-panel">
                        <h3>⚙️ Instellingen</h3>
                        <p>Systeem configuratie en instellingen</p>
                        <a href="/EduLearn/public/admin/settings" class="btn">Instellingen</a>
                    </div>
                </div>
            </section>
            
            <section class="admin-actions">
                <h2>🚀 Snelle Acties</h2>
                <div class="action-buttons">
                    <a href="/EduLearn/public/admin/users" class="btn">👥 Alle Gebruikers</a>
                    <a href="/EduLearn/public/admin/backup" class="btn">💾 Backup Maken</a>
                    <a href="/EduLearn/public/admin/logs" class="btn">📋 Logs Bekijken</a>
                </div>
            </section>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
    
    /**
     * Fallback dashboard voor onbekende rollen
     */
    private static function RenderDefaultDashboard($data, $userName) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>📋 Dashboard - Welkom <?= htmlspecialchars($userName) ?>!</h1>
            
            <div class="default-dashboard">
                <h2>🔍 Onbekende Gebruikersrol</h2>
                <p>Je account heeft een onbekende rol. Neem contact op met de beheerder.</p>
                
                <div class="contact-info">
                    <h3>📞 Contact Opnemen</h3>
                    <p>E-mail: admin@edulearn.nl</p>
                    <p>Telefoon: 020-1234567</p>
                </div>
                
                <div class="basic-actions">
                    <a href="/EduLearn/public/news" class="btn">📰 Nieuws Bekijken</a>
                    <a href="/EduLearn/public/logout" class="btn">🚪 Uitloggen</a>
                </div>
            </div>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
