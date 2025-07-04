<?php
require_once dirname(__DIR__) . '/../config/dbConnect.php';
require_once __DIR__ . '/../models/SalesModel.php';
require_once __DIR__ . '/../views/homepage.php';

class HomepageController
{
    public static function execute()
    {
        // Zorg dat de promotietabel bestaat
        SalesModel::initializeDatabase();

        // Haal de laatste promotie op
        $mainSale = SalesModel::getMainSale();

        // Gebruik de view-klasse om de homepage weer te geven
        HomepageView::Render($mainSale);
    }
}
