<?php
// public/index.php

// Toon fouten voor debugging (but suppress deprecation warnings for cleaner output)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

// Start de router entry point
require_once '../core /routing_entry.php';
