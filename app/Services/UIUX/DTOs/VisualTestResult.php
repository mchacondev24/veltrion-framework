<?php

namespace Veltrion\Services\UIUX\DTOs;

class VisualTestResult
{
    public function __construct(
        public readonly string $screenId,
        public readonly string $platform,
        public readonly string $theme,
        public readonly string $breakpoint,
        public readonly bool $isPassed,
        public readonly ?string $snapshotPath = null,
        public readonly ?string $diffImagePath = null,
        public readonly float $mismatchPercentage = 0.0,
        public readonly array $logs = []
    ) {}

    public function toArray(): array
    {
        return [
            'screen_id' => $this->screenId,
            'platform' => $this->platform,
            'theme' => $this->theme,
            'breakpoint' => $this->breakpoint,
            'is_passed' => $this->isPassed,
            'snapshot_path' => $this->snapshotPath,
            'diff_image_path' => $this->diffImagePath,
            'mismatch_percentage' => $this->mismatchPercentage,
            'logs' => $this->logs,
        ];
    }
}
