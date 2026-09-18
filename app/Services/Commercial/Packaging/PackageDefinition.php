<?php
namespace Veltrion\Services\Commercial\Packaging;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  class PackageDefinition { public const TYPE_SOURCE = 'SOURCE'; public const TYPE_PROTECTED_SOURCE = 'PROTECTED_SOURCE'; public const TYPE_WEB_PACKAGE = 'WEB_PACKAGE'; public const TYPE_PHAR = 'PHAR'; public const TYPE_WINDOWS_PACKAGE = 'WINDOWS_PACKAGE'; public const TYPE_INSTALLER = 'INSTALLER'; public function __construct( public string $productName, public string $version, public string $type = self::TYPE_PROTECTED_SOURCE, public bool $licenseRequired = true, public bool $protectionEnabled = true, public string $profile = 'commercial', public array $includedPaths = ['app', 'bootstrap', 'cli', 'composer.json'], public array $excludedPaths = ['.env', '.git', 'storage/logs', '*.key', '*.pem'] ) {} } 