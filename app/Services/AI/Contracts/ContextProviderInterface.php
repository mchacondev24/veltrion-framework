<?php
namespace Veltrion\Services\AI\Contracts;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  interface ContextProviderInterface { public function buildContext(string $query, array $options = []): string; public function getProjectStats(): array; public function refreshCache(): void; public function clearCache(): void; } 