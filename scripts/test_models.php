<?php
// scripts/test_models.php - Test all models

require_once __DIR__ . '/../config/dbConnect.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/SalesModel.php';
require_once __DIR__ . '/../app/models/NewsModel.php';

echo "Testing models...\n\n";

// Test SalesModel
echo "Testing SalesModel::getMainSale():\n";
$mainSale = SalesModel::getMainSale();
if ($mainSale) {
    echo "✅ SalesModel works - Product: " . ($mainSale['product_name'] ?? 'Unknown') . "\n";
    echo "   Amount: €" . number_format($mainSale['amount'] ?? 0, 2, ',', '.') . "\n";
} else {
    echo "❌ SalesModel failed\n";
}

echo "\nTesting NewsModel::getLatestNewsStory():\n";
$latestNews = NewsModel::getLatestNewsStory();
if ($latestNews) {
    echo "✅ NewsModel works - Title: " . ($latestNews['title'] ?? 'Unknown') . "\n";
    echo "   Content length: " . strlen($latestNews['content'] ?? '') . " characters\n";
} else {
    echo "❌ NewsModel failed\n";
}

echo "\nTesting User::findByEmail():\n";
$user = User::findByEmail('alice@student.com'); // Use existing email
if ($user) {
    echo "✅ User model works - Found user: " . ($user['name'] ?? 'Unknown') . "\n";
    echo "   Role: " . ($user['role'] ?? 'Unknown') . "\n";
} else {
    echo "❌ User model failed or no user found\n";
}

echo "\n✅ All model tests completed!\n";
?>
