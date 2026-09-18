<?php
namespace Veltrion\Services\Template\DTOs;

use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Enums\TemplateStatus;

class TemplateManifest
{
    /**
     * @param string $id Unique identifier (e.g. 'tpl-php-api-v1')
     * @param string $name Name of blueprint
     * @param string $version SemVer version (e.g. '1.0.0')
     * @param PlatformType $platform Target platform
     * @param string $language Primary language
     * @param string $architecture Architecture style (e.g., 'Clean Architecture', 'Feature First', 'MVVM')
     * @param TemplateStatus $status Lifecycle status
     * @param CapabilityMatrix $capabilities Platform capability matrix
     * @param array<string, string> $dependencies Default package dependencies
     * @param array<string, string> $structure File structure map (relative path => description)
     * @param array<string, FeaturePack> $supportedFeaturePacks Available feature packs
     * @param array<string, string> $qualityGates Recommended quality gate thresholds
     * @param string $description Detailed description
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $version,
        public PlatformType $platform,
        public string $language,
        public string $architecture,
        public TemplateStatus $status,
        public CapabilityMatrix $capabilities,
        public array $dependencies = [],
        public array $structure = [],
        public array $supportedFeaturePacks = [],
        public array $qualityGates = [],
        public string $description = ''
    ) {}

    public function toArray(): array
    {
        $featurePacksArr = [];
        foreach ($this->supportedFeaturePacks as $key => $pack) {
            $featurePacksArr[$key] = $pack instanceof FeaturePack ? $pack->toArray() : $pack;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'version' => $this->version,
            'platform' => $this->platform->value,
            'language' => $this->language,
            'architecture' => $this->architecture,
            'status' => $this->status->value,
            'capabilities' => $this->capabilities->toArray(),
            'dependencies' => $this->dependencies,
            'structure' => $this->structure,
            'supported_feature_packs' => $featurePacksArr,
            'quality_gates' => $this->qualityGates,
            'description' => $this->description,
        ];
    }
}
