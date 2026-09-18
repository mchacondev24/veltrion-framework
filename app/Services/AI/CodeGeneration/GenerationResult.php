<?php
namespace Veltrion\Services\AI\CodeGeneration;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  class GenerationResult { private GenerationPlan $plan; private array $filesToCreateOrModify; private string $diffSummary; private array $warnings; public function __construct(GenerationPlan $plan, array $filesToCreateOrModify = [], string $diffSummary = '', array $warnings = []) { $this->plan = $plan; $this->filesToCreateOrModify = $filesToCreateOrModify; $this->diffSummary = $diffSummary; $this->warnings = $warnings; } public function getPlan(): GenerationPlan { return $this->plan; } public function getFiles(): array { return $this->filesToCreateOrModify; } public function getDiffSummary(): string { return $this->diffSummary; } public function getWarnings(): array { return $this->warnings; } } 