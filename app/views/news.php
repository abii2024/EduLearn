<?php

class NewsView {
    public static function Render($latestNewsPost = null) {
        include_once __DIR__ . "/shared/header.php";
        include_once __DIR__ . "/shared/navbar.php";
        ?>
        <main class="container">
            <h1>📰 Laatste Nieuws</h1>

            <?php if (isset($latestNewsPost)) : ?>
                <article class="news-article">
                    <h2><?= htmlspecialchars($latestNewsPost['title']) ?></h2>
                    <p class="date">Geplaatst op: <?= date('d-m-Y', strtotime($latestNewsPost['date'])) ?></p>
                    <div class="body">
                        <?= nl2br(htmlspecialchars($latestNewsPost['body'])) ?>
                    </div>
                </article>
            <?php else : ?>
                <p>Er zijn momenteel geen nieuwsberichten beschikbaar.</p>
            <?php endif; ?>
        </main>
        <?php
        include_once __DIR__ . "/shared/footer.php";
    }
}
