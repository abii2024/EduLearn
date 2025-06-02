<?php
require_once __DIR__ . '/../models/SalesModel.php';

class HomepageController
{
    public static function execute()
    {
        // Zorg dat de promotietabel bestaat
        SalesModel::initializeDatabase();

        // Haal de laatste promotie op
        $mainSale = SalesModel::getMainSale();

        // Toon de homepage view met promotie (als die er is)
        include_once __DIR__ . '/../views/homepage.php';
    }
}
