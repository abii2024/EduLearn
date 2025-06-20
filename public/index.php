<?php
// Zorg dat foutmeldingen zichtbaar zijn tijdens development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start de router via het entrypoint
require_once __DIR__ . '/../app/core/routing_entry.php';
