<?php
if (!defined('rinder')) { die('Direct access not permitted'); }

class HomepageView {
    public static function Render($mainSale = null) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>Welkom bij EduLearn 🎓</h1>
            <p>Ontdek een nieuwe manier van leren! EduLearn is het online platform voor studenten en docenten.</p>

            <section class="highlight">
                <h2>📢 Actueel aanbod</h2>
                <?php if (isset($mainSale) && is_array($mainSale) && !empty($mainSale)) : ?>
                    <div class="sale-box">
                        <h3><?= htmlspecialchars($mainSale['product_name'] ?? 'Onbekend product') ?></h3>
                        <p>Ontdek onze premium cursus en verbeter je vaardigheden!</p>
                        <strong>Prijs: €<?= number_format($mainSale['amount'] ?? 0, 2, ',', '.') ?></strong>
                        <p><small>Datum: <?= date('d-m-Y', strtotime($mainSale['sale_date'] ?? 'now')) ?></small></p>
                    </div>
                <?php else : ?>
                    <p>Er is op dit moment geen promotie beschikbaar.</p>
                <?php endif; ?>
            </section>

            <section class="cta">
                <?php if (isset($_SESSION['user'])): ?>
                    <h2>👋 Welkom terug, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Gebruiker') ?>!</h2>
                    <p>Je bent ingelogd als <?= htmlspecialchars($_SESSION['user']['role'] ?? 'gebruiker') ?>.</p>
                    <a href="/EduLearn/public/dashboard" class="btn">Ga naar Dashboard</a>
                    <a href="/EduLearn/public/news" class="btn">Bekijk Nieuws</a>
                <?php else: ?>
                    <h2>🔐 Toegang tot je dashboard</h2>
                    <a href="/EduLearn/public/login" class="btn">Inloggen</a>
                    <a href="/EduLearn/public/register" class="btn">Registreren</a>
                <?php endif; ?>
            </section>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
