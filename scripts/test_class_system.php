<?php
// Test script to verify class management system
require_once __DIR__ . '/../config/dbConnect.php';
require_once __DIR__ . '/../app/models/ClassModel.php';
require_once __DIR__ . '/../app/models/Lesson.php';

echo "Testing class management system...\n";

try {
    // Test ClassModel
    echo "Testing ClassModel...\n";
    $classes = ClassModel::getByTeacher(2); // Bob Docent
    echo "Found " . count($classes) . " classes for teacher 2\n";
    
    if (!empty($classes)) {
        $firstClass = $classes[0];
        echo "First class: " . $firstClass['name'] . "\n";
        
        // Test students in class
        $students = ClassModel::getStudentsInClass($firstClass['id']);
        echo "Students in class: " . count($students) . "\n";
        
        // Test available students
        $availableStudents = ClassModel::getAvailableStudents($firstClass['id']);
        echo "Available students: " . count($availableStudents) . "\n";
    }
    
    // Test updated Lesson model
    echo "Testing updated Lesson model...\n";
    $lessons = Lesson::getAllForStudent(1); // Alice Student
    echo "Found " . count($lessons) . " lessons for student 1\n";
    
    if (!empty($lessons)) {
        echo "First lesson details:\n";
        echo "- Title: " . ($lessons[0]['title'] ?? 'Unknown') . "\n";
        echo "- Class: " . ($lessons[0]['class_name'] ?? 'Unknown') . "\n";
        echo "- Teacher: " . ($lessons[0]['teacher_name'] ?? 'Unknown') . "\n";
    }
    
    // Test lessons by teacher
    $teacherLessons = Lesson::getAllByTeacher(2);
    echo "Found " . count($teacherLessons) . " lessons for teacher 2\n";
    
    echo "✅ Class management system test successful!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
