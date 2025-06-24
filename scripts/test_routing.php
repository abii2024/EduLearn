<?php
// Test the full routing system
require_once __DIR__ . '/../core /routing_entry.php';

echo "Testing full routing system...\n";

// Simulate a request to /classes/1
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/EduLearn/public/classes/1';

// Mock a teacher session
$_SESSION['user'] = [
    'id' => 2,
    'name' => 'Test Teacher',
    'role' => 'teacher'
];

echo "Simulating request to /classes/1 with teacher session...\n";

try {
    // Get the router instance from routes.php
    $router = require_once __DIR__ . '/../core /routes.php';
    
    // Dispatch the route
    $uri = '/classes/1';
    $method = 'GET';
    
    echo "Dispatching $method $uri...\n";
    $router->dispatch($method, $uri);
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
