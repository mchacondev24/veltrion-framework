<?php
namespace Veltrion\Services\AI\Orchestrator\Contracts;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  interface OrchestratorInterface { public function executeWorkflow(string $prompt, string $workflowType = 'feature', array $options = []): array; public function executeFeatureFile(string $filePath, string $workflowType = 'full', array $options = []): array; public function resumeWorkflow(string $workflowId): array; public function cancelWorkflow(string $workflowId): array; public function getStatus(?string $workflowId = null): array; } 