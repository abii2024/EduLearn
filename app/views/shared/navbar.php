<?php if (!defined('rinder')) { die('Direct access not permitted'); } ?>
<nav class="nav">
    <div class="nav-logo">
        <p>EduLearn</p>
    </div>

    <div class="nav-menu">
        <ul>
            <li><a href="/EduLearn/public/" class="link">Home</a></li>
            <li><a href="/EduLearn/public/news" class="link">Nieuws</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] === 'student'): ?>
                    <li><a href="/EduLearn/public/dashboard" class="link">Dashboard</a></li>
                <?php elseif ($_SESSION['user']['role'] === 'teacher'): ?>
                    <li><a href="/EduLearn/public/teacher-admin" class="link">👥 Docent Admin</a></li>
                    <li><a href="/EduLearn/public/dashboard" class="link">Docentenpaneel</a></li>
                <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
                    <li><a href="/EduLearn/public/admin" class="link">🔧 Admin Panel</a></li>
                    <li><a href="/EduLearn/public/dashboard" class="link">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="/EduLearn/public/logout" class="link">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="/EduLearn/public/login" class="link">Inloggen</a></li>
                <li><a href="/EduLearn/public/register" class="link">Registreren</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
