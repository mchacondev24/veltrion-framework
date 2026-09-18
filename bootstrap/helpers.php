<?php
/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
 if (!function_exists('processEnv')) { function processEnv(string $key, string $default = ''): string { $val = getenv($key); if ($val !== false && $val !== '') { return $val; } if (isset($_ENV[$key]) && $_ENV[$key] !== '') { return $_ENV[$key]; } return $default; } } 