<nav class="nav">
    <div class="nav-logo">
        <p>EduLearn</p>
    </div>

    <div class="nav-menu">
        <ul>
            <li><a href="/" class="link">Home</a></li>
            <li><a href="/news" class="link">Nieuws</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] === 'student'): ?>
                    <li><a href="/dashboard" class="link">Dashboard</a></li>
                <?php elseif ($_SESSION['user']['role'] === 'teacher'): ?>
                    <li><a href="/dashboard" class="link">Docentenpaneel</a></li>
                <?php endif; ?>
                <li><a href="/logout" class="link">Uitloggen</a></li>
            <?php else: ?>
                <li><a href="/login" class="link">Inloggen</a></li>
                <li><a href="/register" class="link">Registreren</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
