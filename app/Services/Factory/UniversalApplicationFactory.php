<?php

namespace Veltrion\Services\Factory;

use Veltrion\Services\Template\TemplateEngine;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\UIUX\UIUXEngine;
use Veltrion\Services\UIUX\DTOs\BrandingConfig;

class UniversalApplicationFactory
{
    private TemplateEngine $templateEngine;
    private UIUXEngine $uiuxEngine;
    private string $projectRoot;

    public function __construct(?string $projectRoot = null)
    {
        $this->projectRoot = $projectRoot ?? __DIR__ . '/../../../';
        $this->templateEngine = new TemplateEngine();
        $this->uiuxEngine = new UIUXEngine();
    }

    /**
     * Creates a complete multi-platform application with project skeleton + full UI/UX
     */
    public function createApplication(
        string $appName,
        string $platform,
        string $outputDir,
        array $entities = [],
        ?BrandingConfig $branding = null,
        bool $dryRun = false
    ): array {
        $branding = $branding ?? new BrandingConfig(appName: $appName);

        // 1. Template Resolution and Generation
        $platEnum = match(strtolower($platform)) {
            'angular' => PlatformType::ANGULAR,
            'react' => PlatformType::REACT,
            'android' => PlatformType::ANDROID,
            'maui' => PlatformType::MAUI,
            'flutter' => PlatformType::FLUTTER,
            default => PlatformType::PHP_API,
        };

        $tmplResult = $this->templateEngine->createProjectFromTemplate(
            platform: $platEnum,
            outputPath: $outputDir,
            variables: ['app_name' => $appName]
        );

        // 2. UI/UX Generation
        $uiResult = $this->uiuxEngine->generateFullFeatureUI(
            featureId: "FEAT-" . strtoupper(substr(md5($appName), 0, 8)),
            platform: $platform,
            entities: $entities,
            branding: $branding,
            outputDir: $outputDir,
            dryRun: $dryRun
        );

        return [
            'app_name' => $appName,
            'platform' => $platform,
            'output_dir' => $outputDir,
            'template_result' => $tmplResult,
            'uiux_result' => $uiResult,
            'status' => 'SUCCESS'
        ];
    }
}
