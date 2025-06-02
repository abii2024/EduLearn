<?php include_once __DIR__ . "/shared/header.php"; ?>
<?php include_once __DIR__ . "/shared/navbar.php"; ?>

<main class="container">
    <h1>Welkom bij EduLearn 🎓</h1>
    <p>Ontdek een nieuwe manier van leren! EduLearn is het online platform voor studenten en docenten.</p>

    <section class="highlight">
        <h2>📢 Actueel aanbod</h2>
        <?php if (isset($mainSale)) : ?>
            <div class="sale-box">
                <h3><?= htmlspecialchars($mainSale['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($mainSale['body'])) ?></p>
                <strong>Prijs: €<?= number_format($mainSale['price'], 2, ',', '.') ?></strong>
                <p><small>Datum: <?= date('d-m-Y', strtotime($mainSale['date'])) ?></small></p>
            </div>
        <?php else : ?>
            <p>Er is op dit moment geen promotie beschikbaar.</p>
        <?php endif; ?>
    </section>

    <section class="cta">
        <h2>🔐 Toegang tot je dashboard</h2>
        <a href="/login" class="btn">Inloggen</a>
        <a href="/register" class="btn">Registreren</a>
    </section>
</main>

<?php include_once __DIR__ . "/shared/footer.php"; ?>
