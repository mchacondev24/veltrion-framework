<?php
namespace Veltrion\Services\Commercial\Protection\Contracts;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  interface ProtectionProviderInterface { public function getName(): string; public function isAvailable(): bool; public function protectDirectory(string $sourceDir, string $targetDir, array $options = []): array; } 