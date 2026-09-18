<?php
namespace Veltrion\Adapters\Repositories;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  use PDO; use Throwable; use Veltrion\Domain\Repositories\UnitOfWorkInterface; class SQLiteUnitOfWork implements UnitOfWorkInterface { private PDO $pdo; public function __construct(PDO $pdo) { $this->pdo = $pdo; } public function begin(): void { if (!$this->pdo->inTransaction()) { $this->pdo->beginTransaction(); } } public function commit(): void { if ($this->pdo->inTransaction()) { $this->pdo->commit(); } } public function rollback(): void { if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); } } public function execute(callable $work) { $this->begin(); try { $result = $work(); $this->commit(); return $result; } catch (Throwable $e) { $this->rollback(); throw $e; } } } 