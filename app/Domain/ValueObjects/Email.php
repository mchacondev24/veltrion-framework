<?php
namespace Veltrion\Domain\ValueObjects;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  use InvalidArgumentException; class Email { private string $value; public function __construct(string $value) { if (!filter_var($value, FILTER_VALIDATE_EMAIL)) { throw new InvalidArgumentException("Dirección de correo electrónico inválida: {$value}"); } $this->value = strtolower(trim($value)); } public function getValue(): string { return $this->value; } public function __toString(): string { return $this->value; } } 