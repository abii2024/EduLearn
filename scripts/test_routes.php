<?php
// Final test for class management system routes
echo "Testing class management routes...\n";

// Test data
$routes_to_test = [
    '/debug/456' => 'Debug route test',
    '/classes' => 'Classes list',
    '/classes/create' => 'Create class form',
    '/classes/1' => 'Class details page'
];

foreach ($routes_to_test as $route => $description) {
    echo "Testing: $description ($route)\n";
    
    $url = "http://localhost/EduLearn/public$route";
    $response = @file_get_contents($url, false, stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]));
    
    if ($response !== false) {
        $status = 'SUCCESS';
        if (strpos($response, '404') !== false) {
            $status = 'NOT_FOUND';
        } elseif (strpos($response, 'Location:') !== false) {
            $status = 'REDIRECT';
        }
        echo "  → $status\n";
    } else {
        echo "  → CONNECTION_ERROR\n";
    }
    
    sleep(1); // Small delay between requests
}

echo "✅ Route testing completed!\n";
