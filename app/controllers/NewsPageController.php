<?php
require_once dirname(__DIR__) . '/../config/dbConnect.php';
require_once __DIR__ . '/../models/NewsModel.php';
require_once __DIR__ . '/../views/news.php';

class NewsPageController
{
    public static function execute()
    {
        // Zorg dat de database tabel bestaat
        NewsModel::initializeDatabase();

        // Haal het laatste nieuwsbericht op
        $latestNewsPost = NewsModel::getLatestNewsStory();

        // Toon de view via een view class
        NewsView::Render($latestNewsPost);
    }
}
