<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

include_once __DIR__ . "/shared/header.php";
include_once __DIR__ . "/shared/navbar.php";
?>

<main class="container">
    <h1>🔧 Administrator Dashboard</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>📊 Gebruikersstatistieken</h3>
            <ul>
                <li><strong>Totaal gebruikers:</strong> <?= $totalUsers ?></li>
                <li><strong>Studenten:</strong> <?= $studentCount ?></li>
                <li><strong>Docenten:</strong> <?= $teacherCount ?></li>
                <li><strong>Administrators:</strong> <?= $adminCount ?></li>
            </ul>
        </div>
    </div>

    <div class="admin-actions">
        <h2>🛠️ Beheeracties</h2>
        <div class="action-buttons">
            <a href="/EduLearn/public/admin/users" class="btn">👥 Gebruikers beheren</a>
            <a href="/EduLearn/public/classes" class="btn">📚 Klassen beheren</a>
            <a href="/EduLearn/public/news" class="btn">📰 Nieuws beheren</a>
        </div>
    </div>

    <div class="admin-info">
        <h2>ℹ️ Administrator Informatie</h2>
        <p>Als administrator heb je toegang tot alle functies van het systeem:</p>
        <ul>
            <li>Gebruikers beheren (promoveren, verwijderen)</li>
            <li>Alle klassen en lessen bekijken</li>
            <li>Systeemstatistieken inzien</li>
            <li>Content moderatie</li>
        </ul>
    </div>
</main>

<style>
.dashboard-stats {
    margin: 20px 0;
}

.stat-card {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
    margin-bottom: 20px;
}

.stat-card h3 {
    margin-top: 0;
    color: #007bff;
}

.stat-card ul {
    list-style: none;
    padding: 0;
}

.stat-card li {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}

.stat-card li:last-child {
    border-bottom: none;
}

.admin-actions {
    margin: 30px 0;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.action-buttons .btn {
    display: inline-block;
    padding: 12px 20px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.action-buttons .btn:hover {
    background: #0056b3;
}

.admin-info {
    background: #e8f4fd;
    padding: 20px;
    border-radius: 8px;
    margin-top: 30px;
}

.admin-info h2 {
    color: #0c5460;
    margin-top: 0;
}

.admin-info ul {
    margin: 15px 0;
}

.admin-info li {
    margin: 5px 0;
}

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
</style>

<?php include_once __DIR__ . "/shared/footer.php"; ?>
