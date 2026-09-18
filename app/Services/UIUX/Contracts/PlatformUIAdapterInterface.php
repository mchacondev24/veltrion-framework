<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\DTOs\DesignSystem;
use Veltrion\Services\UIUX\DTOs\Theme;
use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\DTOs\UIGenerationResult;

interface PlatformUIAdapterInterface
{
    public function getPlatformName(): string;
    public function getSupportedExtensions(): array;
    public function renderScreen(ScreenModel $screen, DesignSystem $designSystem, ?Theme $theme = null): string;
    public function renderComponent(UIComponent $component, DesignSystem $designSystem): string;
    public function renderNavigation(NavigationTree $navigation, DesignSystem $designSystem): string;
    public function renderDesignSystem(DesignSystem $designSystem): array; // filename => content
    public function generateProjectUI(
        array $screens,
        NavigationTree $navigation,
        DesignSystem $designSystem,
        string $outputDir,
        bool $dryRun = false
    ): UIGenerationResult;
}
