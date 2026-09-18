<?php

namespace Veltrion\Services\UIUX;

use Veltrion\Services\UIUX\Adapters\PlatformUIAdapterRegistry;
use Veltrion\Services\UIUX\Accessibility\AccessibilityEngine;
use Veltrion\Services\UIUX\Contracts\UIUXEngineInterface;
use Veltrion\Services\UIUX\DesignSystem\DesignSystemEngine;
use Veltrion\Services\UIUX\DTOs\BrandingConfig;
use Veltrion\Services\UIUX\DTOs\DesignSystem;
use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\DTOs\UIGenerationResult;
use Veltrion\Services\UIUX\DTOs\UXAnalysisResult;
use Veltrion\Services\UIUX\Planning\NavigationPlanner;
use Veltrion\Services\UIUX\Planning\ScreenPlanner;
use Veltrion\Services\UIUX\Planning\UserFlowGenerator;
use Veltrion\Services\UIUX\Planning\UXAnalyzer;
use Veltrion\Services\UIUX\Testing\VisualTestEngine;
use Veltrion\Services\UIUX\Validation\UIValidator;

class UIUXEngine implements UIUXEngineInterface
{
    private UXAnalyzer $analyzer;
    private UserFlowGenerator $flowGenerator;
    private ScreenPlanner $screenPlanner;
    private NavigationPlanner $navPlanner;
    private DesignSystemEngine $designSystemEngine;
    private AccessibilityEngine $a11yEngine;
    private UIValidator $validator;
    private PlatformUIAdapterRegistry $adapterRegistry;
    private VisualTestEngine $visualTestEngine;

    public function __construct()
    {
        $this->analyzer = new UXAnalyzer();
        $this->flowGenerator = new UserFlowGenerator();
        $this->screenPlanner = new ScreenPlanner();
        $this->navPlanner = new NavigationPlanner();
        $this->designSystemEngine = new DesignSystemEngine();
        $this->a11yEngine = new AccessibilityEngine();
        $this->validator = new UIValidator();
        $this->adapterRegistry = new PlatformUIAdapterRegistry();
        $this->visualTestEngine = new VisualTestEngine();
    }

    public function analyzeRequirements(string $featureId, array $requirements = []): UXAnalysisResult
    {
        return $this->analyzer->analyzeFeature($featureId, $requirements);
    }

    public function buildDesignSystem(BrandingConfig $branding): DesignSystem
    {
        return $this->designSystemEngine->generateDesignSystem($branding);
    }

    public function planScreensAndNavigation(UXAnalysisResult $analysis, array $entities = []): array
    {
        $screens = $this->screenPlanner->planScreensFromFlows($analysis->flows, $entities);
        $navigation = $this->navPlanner->planNavigation($screens);

        return [
            'screens' => $screens,
            'navigation' => $navigation,
        ];
    }

    public function validateUI(array $screens, NavigationTree $navigation, array $flows = []): array
    {
        return $this->validator->validateArchitecture($screens, $navigation, $flows);
    }

    public function auditAccessibility(array $screens, DesignSystem $designSystem): array
    {
        $findings = [];
        foreach ($screens as $screen) {
            $f = $this->a11yEngine->auditScreen($screen, $designSystem);
            if (!empty($f)) {
                $findings[$screen->id] = $f;
            }
        }
        return $findings;
    }

    public function generatePlatformUI(
        string $platform,
        array $screens,
        NavigationTree $navigation,
        DesignSystem $designSystem,
        string $outputDir,
        bool $dryRun = false
    ): UIGenerationResult {
        $adapter = $this->adapterRegistry->get($platform);
        return $adapter->generateProjectUI($screens, $navigation, $designSystem, $outputDir, $dryRun);
    }

    /**
     * Executes the complete end-to-end UI/UX pipeline for a feature and platform
     */
    public function generateFullFeatureUI(
        string $featureId,
        string $platform,
        array $entities,
        BrandingConfig $branding,
        string $outputDir,
        bool $dryRun = false
    ): array {
        $analysis = $this->analyzeRequirements($featureId);
        $designSystem = $this->buildDesignSystem($branding);
        $planned = $this->planScreensAndNavigation($analysis, $entities);
        $screens = $planned['screens'];
        $navigation = $planned['navigation'];

        $validationIssues = $this->validateUI($screens, $navigation, $analysis->flows);
        $a11yFindings = $this->auditAccessibility($screens, $designSystem);

        $genResult = $this->generatePlatformUI(
            platform: $platform,
            screens: $screens,
            navigation: $navigation,
            designSystem: $designSystem,
            outputDir: $outputDir,
            dryRun: $dryRun
        );

        $report = $this->generateReport($analysis, $screens, $navigation, $validationIssues, $a11yFindings, $genResult);

        return [
            'analysis' => $analysis,
            'design_system' => $designSystem,
            'screens' => $screens,
            'navigation' => $navigation,
            'validation_issues' => $validationIssues,
            'accessibility_findings' => $a11yFindings,
            'generation_result' => $genResult,
            'report_markdown' => $report,
        ];
    }

    public function generateReport(
        UXAnalysisResult $analysis,
        array $screens,
        NavigationTree $navigation,
        array $validationIssues,
        array $a11yFindings,
        ?UIGenerationResult $genResult = null
    ): string {
        $md = "# 🎨 VELTRION UI/UX GENERATION REPORT\n\n";
        $md .= "**Feature**: `{$analysis->featureId}`\n";
        $md .= "**Date**: " . date('Y-m-d H:i:s') . "\n";
        $md .= "**Platform**: " . ($genResult ? $genResult->platform : 'N/A') . "\n\n";

        $md .= "## 1. User Roles & Personas\n";
        foreach ($analysis->personas as $persona) {
            $md .= "- **{$persona->name}** ({$persona->archetype}): {$persona->bio}\n";
        }
        $md .= "\n";

        $md .= "## 2. Planned Screens (" . count($screens) . " total)\n";
        foreach ($screens as $id => $s) {
            $md .= "- **{$s->title}** (`{$s->route}`): {$s->purpose} [Layout: `{$s->layoutType}`]\n";
        }
        $md .= "\n";

        $md .= "## 3. Architecture Validation\n";
        if (empty($validationIssues)) {
            $md .= "✔ **PASSED**: No dead-ends, orphaned screens or missing UI states found.\n";
        } else {
            foreach ($validationIssues as $issue) {
                $md .= "- ⚠ [{$issue['severity']}] {$issue['type']} in `{$issue['screen_id']}`: {$issue['message']}\n";
            }
        }
        $md .= "\n";

        $md .= "## 4. Accessibility (WCAG 2.1 AA)\n";
        if (empty($a11yFindings)) {
            $md .= "✔ **PASSED**: All screens pass contrast, touch target and label standards.\n";
        } else {
            foreach ($a11yFindings as $screenId => $findings) {
                foreach ($findings as $f) {
                    $md .= "- [{$f->severity->value}] `{$f->ruleId}` on screen `{$screenId}`: {$f->message}\n";
                }
            }
        }
        $md .= "\n";

        if ($genResult) {
            $md .= "## 5. Generated Artifacts (" . count($genResult->generatedFiles) . " files)\n";
            foreach (array_keys($genResult->generatedFiles) as $file) {
                $md .= "- `{$file}`\n";
            }
        }

        return $md;
    }
}
