<?php
// core/helpers.php - Utility functions

/**
 * Safe htmlspecialchars that handles null values
 */
function safe_html($value, $default = '') {
    return htmlspecialchars($value ?? $default, ENT_QUOTES, 'UTF-8');
}

/**
 * Safe date formatting that handles null values
 */
function safe_date($value, $format = 'd-m-Y', $default = null) {
    if (empty($value)) {
        return $default ? date($format, strtotime($default)) : date($format);
    }
    return date($format, strtotime($value));
}

/**
 * Safe number formatting that handles null values
 */
function safe_number($value, $decimals = 2, $default = 0) {
    return number_format($value ?? $default, $decimals, ',', '.');
}

/**
 * Check if array key exists and is not empty
 */
function array_get($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}
?>
