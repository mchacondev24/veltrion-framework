<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\BrandingConfig;
use Veltrion\Services\UIUX\DTOs\UXAnalysisResult;
use Veltrion\Services\UIUX\DTOs\DesignSystem;
use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\DTOs\UIGenerationResult;

interface UIUXEngineInterface
{
    public function analyzeRequirements(string $featureId, array $requirements = []): UXAnalysisResult;
    public function buildDesignSystem(BrandingConfig $branding): DesignSystem;
    public function planScreensAndNavigation(UXAnalysisResult $analysis, array $entities = []): array; // ['screens' => ..., 'navigation' => ...]
    public function validateUI(array $screens, NavigationTree $navigation, array $flows = []): array;
    public function generatePlatformUI(
        string $platform,
        array $screens,
        NavigationTree $navigation,
        DesignSystem $designSystem,
        string $outputDir,
        bool $dryRun = false
    ): UIGenerationResult;
}
