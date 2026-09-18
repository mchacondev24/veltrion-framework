<?php
namespace Veltrion\Services\Template\DTOs;

use Veltrion\Services\Template\Enums\FeaturePackType;

class FeaturePack
{
    /**
     * @param string $id ID of the feature pack (e.g. 'auth-jwt', 'database-sqlite')
     * @param string $name Human-readable name
     * @param FeaturePackType $type Type category
     * @param string $description Summary of functionality
     * @param array<string, string> $dependencies Added packages/dependencies
     * @param array<string, string> $files Added or overlaid file templates
     * @param array<string, mixed> $config Configuration defaults
     */
    public function __construct(
        public string $id,
        public string $name,
        public FeaturePackType $type,
        public string $description,
        public array $dependencies = [],
        public array $files = [],
        public array $config = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
            'description' => $this->description,
            'dependencies' => $this->dependencies,
            'files' => $this->files,
            'config' => $this->config,
        ];
    }
}
