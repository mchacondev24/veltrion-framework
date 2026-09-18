<?php

namespace Veltrion\Services\UIUX\DTOs;

class UIGenerationResult
{
    /**
     * @param array<string, string> $generatedFiles Path => Relative Path or Content Hash
     * @param array<int, string> $logs
     * @param array<int, string> $warnings
     * @param array<int, string> $errors
     */
    public function __construct(
        public readonly bool $isSuccess,
        public readonly string $platform,
        public readonly string $outputDirectory,
        public readonly array $generatedFiles = [],
        public readonly ?UIDiffResult $diff = null,
        public readonly array $logs = [],
        public readonly array $warnings = [],
        public readonly array $errors = []
    ) {}

    public function toArray(): array
    {
        return [
            'is_success' => $this->isSuccess,
            'platform' => $this->platform,
            'output_directory' => $this->outputDirectory,
            'generated_files' => $this->generatedFiles,
            'diff' => $this->diff?->toArray(),
            'logs' => $this->logs,
            'warnings' => $this->warnings,
            'errors' => $this->errors,
        ];
    }
}
