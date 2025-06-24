<?php
// Test script to verify Assignment model fixes
require_once __DIR__ . '/../config/dbConnect.php';
require_once __DIR__ . '/../app/models/Assignment.php';

echo "Testing Assignment model...\n";

try {
    // Test getRecentByTeacher method - this was the one causing the error
    echo "Testing getRecentByTeacher method...\n";
    $teacherId = 2; // Use teacher ID 2 (Bob Docent) which has lessons
    $assignments = Assignment::getRecentByTeacher($teacherId);
    
    echo "Found " . count($assignments) . " assignments for teacher $teacherId\n";
    
    if (!empty($assignments)) {
        echo "First assignment details:\n";
        print_r($assignments[0]);
    }
    
    echo "✅ Assignment model test successful!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
