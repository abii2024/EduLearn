<?php
// scripts/sample_data.php - Run this to add sample data

require_once __DIR__ . '/../config/dbConnect.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/SalesModel.php';
require_once __DIR__ . '/../app/models/NewsModel.php';

// Initialize tables
User::initializeDatabase();
SalesModel::initializeDatabase();
NewsModel::initializeDatabase();

// Add sample news
$news = new NewsModel(
    'Welkom bij EduLearn!',
    'We verwelkomen alle nieuwe studenten en docenten op ons platform. Ontdek alle functies en begin vandaag nog met leren!',
    date('Y-m-d H:i:s')
);
$news->save();

// Add sample sale
$sale = new SalesModel(
    1,
    'Premium Cursus Programmeren',
    89.99,
    date('Y-m-d')
);
$sale->save();

echo "Sample data added successfully!\n";
?>
