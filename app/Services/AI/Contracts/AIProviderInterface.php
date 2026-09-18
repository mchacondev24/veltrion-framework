<?php
namespace Veltrion\Services\AI\Contracts;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  use Veltrion\Services\AI\DTOs\AIRequest; use Veltrion\Services\AI\DTOs\AIResponse; interface AIProviderInterface { public function getName(): string; public function isAvailable(): bool; public function listModels(): array; public function generate(AIRequest $request): AIResponse; } 