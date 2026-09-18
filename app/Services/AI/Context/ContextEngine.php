<?php
namespace Veltrion\Services\AI\Context;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  use Veltrion\Services\AI\Contracts\ContextProviderInterface; class ContextEngine implements ContextProviderInterface { private ContextBuilder $builder; public function __construct(?ContextBuilder $builder = null) { $this->builder = $builder ?? new ContextBuilder(); } public function buildContext(string $query, array $options = []): string { return $this->builder->build($query, $options); } public function getProjectStats(): array { return $this->builder->getCacheStats(); } public function refreshCache(): void { $this->builder->refreshCache(); } public function clearCache(): void { $this->builder->clearCache(); } } 