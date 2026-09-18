<?php
namespace Veltrion\Services\Commercial\Licensing\Contracts;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  interface MachineIdentityProviderInterface { public function getMachineId(): string; public function getMachineFingerprint(): array; } 