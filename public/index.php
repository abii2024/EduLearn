<?php
// Start de sessie
session_start();

// Zet de base directory voor alle includes
define('BASE_PATH', dirname(__DIR__));

// Autoloading of basic includes
require_once BASE_PATH . '/app/core/router.php';
require_once BASE_PATH . '/app/core/routes.php';
require_once BASE_PATH . '/app/core/controller.php';
require_once BASE_PATH . '/app/core/model.php';
