<?php
namespace Veltrion\Services\AI\FeatureEngine\DTOs;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  class RequirementDTO { public function __construct( public string $id, public string $description, public string $type = 'FUNCTIONAL', public string $priority = 'HIGH', public array $acceptanceCriteria = [], public string $status = 'PLANNED' ) {} public function toArray(): array { return [ 'id' => $this->id, 'description' => $this->description, 'type' => $this->type, 'priority' => $this->priority, 'acceptance_criteria' => $this->acceptanceCriteria, 'status' => $this->status ]; } } 