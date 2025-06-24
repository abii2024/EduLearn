<?php
// Test script for dynamic router functionality
require_once __DIR__ . '/../core /router.php';

echo "Testing dynamic router...\n";

// Create router instance
$router = new Router();

// Add a test route with parameter
$router->get('/test/{id}', function($id) {
    echo "Test route called with ID: $id\n";
});

// Test the route matching
echo "Testing route /test/123:\n";
try {
    // Simulate the dispatch
    $method = 'GET';
    $uri = '/test/123';
    
    // Test our route matching logic manually
    $reflection = new ReflectionClass($router);
    $matchMethod = $reflection->getMethod('matchRoute');
    $matchMethod->setAccessible(true);
    
    $extractMethod = $reflection->getMethod('extractParams');
    $extractMethod->setAccessible(true);
    
    $routesProperty = $reflection->getProperty('routes');
    $routesProperty->setAccessible(true);
    $routes = $routesProperty->getValue($router);
    
    echo "Available routes: " . print_r($routes, true) . "\n";
    
    if (isset($routes['GET']['/test/{id}'])) {
        $isMatch = $matchMethod->invoke($router, '/test/{id}', '/test/123');
        echo "Route matches: " . ($isMatch ? 'YES' : 'NO') . "\n";
        
        if ($isMatch) {
            $params = $extractMethod->invoke($router, '/test/{id}', '/test/123');
            echo "Extracted parameters: " . print_r($params, true) . "\n";
        }
    }
    
    echo "✅ Router test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
