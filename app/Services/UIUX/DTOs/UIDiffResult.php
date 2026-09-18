<?php

namespace Veltrion\Services\UIUX\DTOs;

class UIDiffResult
{
    public function __construct(
        public readonly array $createdFiles = [],
        public readonly array $modifiedFiles = [],
        public readonly array $unchangedFiles = [],
        public readonly array $protectedSkippedFiles = [],
        public readonly array $conflicts = []
    ) {}

    public function toArray(): array
    {
        return [
            'created_files' => $this->createdFiles,
            'modified_files' => $this->modifiedFiles,
            'unchanged_files' => $this->unchangedFiles,
            'protected_skipped_files' => $this->protectedSkippedFiles,
            'conflicts' => $this->conflicts,
        ];
    }
}
