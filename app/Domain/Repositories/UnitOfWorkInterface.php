<?php
namespace Veltrion\Domain\Repositories;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  interface UnitOfWorkInterface { public function begin(): void; public function commit(): void; public function rollback(): void; public function execute(callable $work); } 