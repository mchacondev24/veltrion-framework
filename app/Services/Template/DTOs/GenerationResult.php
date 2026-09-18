<?php
namespace Veltrion\Services\Template\DTOs;

class GenerationResult
{
    /**
     * @param string $templateId ID of generated template
     * @param string $outputPath Output destination path
     * @param array<string> $generatedFiles List of relative paths generated
     * @param array<string> $appliedFeaturePacks Applied feature pack IDs
     * @param array<string, string> $mergedDependencies Final package dependencies
     * @param array<string> $logs Generation execution logs
     */
    public function __construct(
        public string $templateId,
        public string $outputPath,
        public array $generatedFiles = [],
        public array $appliedFeaturePacks = [],
        public array $mergedDependencies = [],
        public array $logs = []
    ) {}

    public function toArray(): array
    {
        return [
            'template_id' => $this->templateId,
            'output_path' => $this->outputPath,
            'files_count' => count($this->generatedFiles),
            'generated_files' => $this->generatedFiles,
            'applied_feature_packs' => $this->appliedFeaturePacks,
            'merged_dependencies' => $this->mergedDependencies,
            'logs' => $this->logs,
        ];
    }
}
