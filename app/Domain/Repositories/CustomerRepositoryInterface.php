<?php
namespace Veltrion\Domain\Repositories;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  use Veltrion\Domain\Entities\Customer; interface CustomerRepositoryInterface { public function findById(int $id): ?Customer; public function findByEmail(string $email): ?Customer; public function findAll(): array; public function save(Customer $customer): Customer; public function delete(int $id): bool; } 