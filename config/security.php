<?php
/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
 return [ 'cipher' => 'AES-256-GCM', 'hash_algo' => PASSWORD_ARGON2ID, 'csrf_protection' => true, 'session_lifetime' => 120, ]; 