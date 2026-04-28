<?php

/**
 * PHPUnit bootstrap file for Horde\Translation
 *
 * Configures PCOV to cover both lib/ and src/ directories.
 *
 * IMPORTANT: PCOV settings must be configured before any code is executed,
 * which means they need to be set via php.ini, .user.ini, or -d flag.
 *
 * To run tests with coverage:
 *   php -d pcov.directory=/path/to/Translation phpunit --coverage-text
 *
 * Or for convenience, from the Translation directory:
 *   php -d pcov.directory=$(pwd) phpunit --coverage-text
 */

// Note: pcov.directory can't be set at runtime, it must be set before PHP starts
// This bootstrap just loads autoloader
require_once __DIR__ . '/../vendor/autoload.php';
