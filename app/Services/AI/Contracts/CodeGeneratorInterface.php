<?php
namespace Veltrion\Services\AI\Contracts;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  use Veltrion\Services\AI\CodeGeneration\GenerationPlan; use Veltrion\Services\AI\CodeGeneration\GenerationResult; interface CodeGeneratorInterface { public function createPlan(string $userRequest, array $contextOptions = []): GenerationPlan; public function generateCode(GenerationPlan $plan): GenerationResult; public function applyChanges(GenerationResult $result): bool; } 