<?php
namespace Veltrion\Services\AI\DTOs;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  class AIRequest { private string $prompt; private string $context; private string $model; private array $options; public function __construct(string $prompt, string $context = '', string $model = '', array $options = []) { $this->prompt = $prompt; $this->context = $context; $this->model = $model; $this->options = $options; } public function getPrompt(): string { return $this->prompt; } public function getContext(): string { return $this->context; } public function getModel(): string { return $this->model; } public function getOptions(): array { return $this->options; } } 