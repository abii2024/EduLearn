<?php
require_once dirname(__DIR__) . '/../config/dbConnect.php';
require_once __DIR__ . '/../models/NewsModel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../views/public_news.php';

class PublicNewsController
{
    public static function execute()
    {
        // Haal nieuws op
        NewsModel::initializeDatabase();
        $news = NewsModel::getAllNews();
        
        // Bepaal gebruiker status
        $userRole = null;
        $isLoggedIn = false;
        
        if (isset($_SESSION['user'])) {
            $isLoggedIn = true;
            $userRole = $_SESSION['user']['role'];
        }
        
        // Toon verschillende views op basis van login status
        if ($isLoggedIn) {
            PublicNewsView::RenderForLoggedIn($news, $userRole);
        } else {
            PublicNewsView::RenderForGuest($news);
        }
    }
}
