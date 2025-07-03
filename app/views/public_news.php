<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class PublicNewsView {
    
    /**
     * View voor ingelogde gebruikers - meer functionaliteit
     */
    public static function RenderForLoggedIn($news, $userRole) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>📰 Nieuws - Welkom <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Gebruiker') ?>!</h1>
            
            <div class="user-info">
                <p>Je bent ingelogd als: <strong><?= htmlspecialchars($userRole) ?></strong></p>
                <p>🎯 Exclusieve content voor jou beschikbaar!</p>
            </div>

            <!-- Exclusive content voor ingelogde gebruikers -->
            <section class="exclusive-content">
                <h2>🔐 Exclusief voor Leden</h2>
                <?php if ($userRole === 'student'): ?>
                    <div class="student-exclusive">
                        <h3>👨‍🎓 Student Update</h3>
                        <p>Nieuwe opdrachten en cursussen zijn beschikbaar in je dashboard!</p>
                        <a href="/EduLearn/public/dashboard" class="btn">Ga naar Dashboard</a>
                    </div>
                <?php elseif ($userRole === 'teacher'): ?>
                    <div class="teacher-exclusive">
                        <h3>👨‍🏫 Docent Update</h3>
                        <p>Nieuwe tools voor klassenbeheer zijn nu beschikbaar!</p>
                        <a href="/EduLearn/public/teacher-admin" class="btn">Docent Admin</a>
                    </div>
                <?php elseif ($userRole === 'admin'): ?>
                    <div class="admin-exclusive">
                        <h3>🔧 Admin Update</h3>
                        <p>Systeem statistieken en nieuwe beheerfuncties zijn toegevoegd!</p>
                        <a href="/EduLearn/public/admin" class="btn">Admin Panel</a>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Alle nieuws items -->
            <section class="news-section">
                <h2>📢 Alle Nieuws</h2>
                <?php if (empty($news)): ?>
                    <p>Nog geen nieuws beschikbaar.</p>
                <?php else: ?>
                    <div class="news-grid">
                        <?php foreach ($news as $item): ?>
                            <div class="news-card premium">
                                <h3><?= htmlspecialchars($item['title'] ?? 'Geen titel') ?></h3>
                                <div class="news-content">
                                    <!-- Volledige content voor ingelogde gebruikers -->
                                    <?= nl2br(htmlspecialchars($item['content'] ?? 'Geen content')) ?>
                                </div>
                                <div class="news-meta">
                                    <small>📅 <?= date('d-m-Y H:i', strtotime($item['created_at'] ?? 'now')) ?></small>
                                    <span class="premium-badge">🔐 Premium</span>
                                </div>
                                
                                <!-- Extra acties voor ingelogde gebruikers -->
                                <div class="news-actions">
                                    <button class="btn btn-small" onclick="shareNews(<?= $item['id'] ?>)">📤 Delen</button>
                                    <button class="btn btn-small" onclick="bookmarkNews(<?= $item['id'] ?>)">🔖 Opslaan</button>
                                    <?php if ($userRole === 'admin'): ?>
                                        <button class="btn btn-small btn-danger" onclick="deleteNews(<?= $item['id'] ?>)">🗑️ Verwijderen</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Personalized recommendations -->
            <section class="recommendations">
                <h2>🎯 Aanbevolen voor jou</h2>
                <div class="recommendation-cards">
                    <?php if ($userRole === 'student'): ?>
                        <div class="rec-card">
                            <h4>📚 Nieuwe Cursussen</h4>
                            <p>Ontdek cursussen die passen bij jouw niveau!</p>
                        </div>
                    <?php elseif ($userRole === 'teacher'): ?>
                        <div class="rec-card">
                            <h4>🎓 Klasbeheer Tips</h4>
                            <p>Nieuwe strategieën voor effectief onderwijs!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
        
        <!-- JavaScript voor ingelogde gebruikers -->
        <script>
            function shareNews(id) {
                alert('Delen van nieuws ID: ' + id);
            }
            
            function bookmarkNews(id) {
                alert('Opslaan van nieuws ID: ' + id);
            }
            
            function deleteNews(id) {
                if (confirm('Weet je zeker dat je dit nieuwsitem wilt verwijderen?')) {
                    // AJAX call naar delete endpoint
                    alert('Verwijderen van nieuws ID: ' + id);
                }
            }
        </script>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
    
    /**
     * View voor niet-ingelogde gebruikers - beperkte functionaliteit
     */
    public static function RenderForGuest($news) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>📰 Nieuws - Welkom Bezoeker!</h1>
            
            <div class="guest-info">
                <p>👋 Welkom! Log in voor exclusieve content en extra functionaliteit.</p>
                <div class="cta-buttons">
                    <a href="/EduLearn/public/login" class="btn">🔐 Inloggen</a>
                    <a href="/EduLearn/public/register" class="btn">📝 Registreren</a>
                </div>
            </div>

            <!-- Beperkte nieuws sectie -->
            <section class="news-section">
                <h2>📢 Openbaar Nieuws</h2>
                <?php if (empty($news)): ?>
                    <p>Nog geen nieuws beschikbaar.</p>
                <?php else: ?>
                    <div class="news-grid">
                        <?php foreach (array_slice($news, 0, 3) as $item): // Maximaal 3 items voor gasten ?>
                            <div class="news-card guest">
                                <h3><?= htmlspecialchars($item['title'] ?? 'Geen titel') ?></h3>
                                <div class="news-content">
                                    <!-- Beperkte content voor gasten -->
                                    <?php 
                                    $content = $item['content'] ?? '';
                                    $preview = substr($content, 0, 150);
                                    echo nl2br(htmlspecialchars($preview));
                                    if (strlen($content) > 150): ?>
                                        <span class="read-more">... <a href="/EduLearn/public/login">Lees meer (login vereist)</a></span>
                                    <?php endif; ?>
                                </div>
                                <div class="news-meta">
                                    <small>📅 <?= date('d-m-Y', strtotime($item['created_at'] ?? 'now')) ?></small>
                                    <span class="guest-badge">👁️ Openbaar</span>
                                </div>
                                
                                <!-- Login prompt -->
                                <div class="login-prompt">
                                    <p>🔐 <a href="/EduLearn/public/login">Log in</a> voor volledige toegang en extra functies!</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($news) > 3): ?>
                        <div class="more-news-prompt">
                            <p>📰 Er zijn nog <?= count($news) - 3 ?> meer nieuwsitems beschikbaar!</p>
                            <a href="/EduLearn/public/login" class="btn">Login voor Volledige Toegang</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>

            <!-- Promo sectie voor gasten -->
            <section class="promo-section">
                <h2>🎯 Waarom EduLearn?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <h4>📚 Uitgebreide Cursussen</h4>
                        <p>Toegang tot honderden cursussen na registratie</p>
                    </div>
                    <div class="feature-card">
                        <h4>👨‍🏫 Expert Docenten</h4>
                        <p>Leer van professionele docenten in je vakgebied</p>
                    </div>
                    <div class="feature-card">
                        <h4>🎓 Certificaten</h4>
                        <p>Verdien certificaten na het voltooien van cursussen</p>
                    </div>
                </div>
                
                <div class="cta-final">
                    <h3>🚀 Klaar om te beginnen?</h3>
                    <a href="/EduLearn/public/register" class="btn btn-large">Gratis Registreren</a>
                </div>
            </section>
        </main>
        
        <!-- Eenvoudige JavaScript voor gasten -->
        <script>
            // Track guest interactions
            document.querySelectorAll('.login-prompt a').forEach(link => {
                link.addEventListener('click', function() {
                    console.log('Guest clicked login from news');
                });
            });
        </script>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
    
    /**
     * Dynamische render method die automatisch de juiste view kiest
     */
    public static function RenderDynamic($news) {
        if (isset($_SESSION['user'])) {
            self::RenderForLoggedIn($news, $_SESSION['user']['role']);
        } else {
            self::RenderForGuest($news);
        }
    }
}
