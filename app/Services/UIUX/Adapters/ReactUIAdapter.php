<?php

namespace Veltrion\Services\UIUX\Adapters;

use Veltrion\Services\UIUX\Contracts\PlatformUIAdapterInterface;
use Veltrion\Services\UIUX\DTOs\DesignSystem;
use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\DTOs\Theme;
use Veltrion\Services\UIUX\DTOs\UIComponent;
use Veltrion\Services\UIUX\DTOs\UIGenerationResult;
use Veltrion\Services\UIUX\Idempotency\FileMetadataManager;

class ReactUIAdapter implements PlatformUIAdapterInterface
{
    public function getPlatformName(): string
    {
        return 'react';
    }

    public function getSupportedExtensions(): array
    {
        return ['tsx', 'ts', 'css'];
    }

    public function renderScreen(ScreenModel $screen, DesignSystem $designSystem, ?Theme $theme = null): string
    {
        $pascalName = str_replace(['.', '-', '_'], '', ucwords($screen->id, '.-_')) . 'Screen';

        $code = "import React, { useState, useEffect } from 'react';\n";
        $code .= "import { Card } from '../components/Card';\n";
        $code .= "import { Button } from '../components/Button';\n";
        $code .= "import { DataGrid } from '../components/DataGrid';\n\n";

        $code .= "/**\n";
        $code .= " * {$screen->title} Screen\n";
        $code .= " * Route: {$screen->route}\n";
        $code .= " * Purpose: {$screen->purpose}\n";
        $code .= " */\n";
        $code .= "export const {$pascalName}: React.FC = () => {\n";
        $code .= "  const [loading, setLoading] = useState<boolean>(false);\n";
        $code .= "  const [error, setError] = useState<string | null>(null);\n\n";

        $code .= "  useEffect(() => {\n";
        $code .= "    // Initialize screen data\n";
        $code .= "  }, []);\n\n";

        $code .= "  return (\n";
        $code .= "    <div className=\"min-h-screen bg-slate-50 text-slate-900 p-6 md:p-8 space-y-6\">\n";
        $code .= "      <header className=\"flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5\">\n";
        $code .= "        <div>\n";
        $code .= "          <h1 className=\"text-2xl md:text-3xl font-bold tracking-tight text-slate-900\">{$screen->title}</h1>\n";
        $code .= "          <p className=\"text-sm text-slate-500 mt-1\">{$screen->purpose}</p>\n";
        $code .= "        </div>\n";

        if (!empty($screen->actions)) {
            $code .= "        <div className=\"flex flex-wrap items-center gap-3\">\n";
            foreach ($screen->actions as $action) {
                $variant = $action->isPrimary ? "bg-blue-600 hover:bg-blue-700 text-white font-medium" : "bg-white border border-slate-300 text-slate-700 hover:bg-slate-50";
                $code .= "          <button className=\"px-4 py-2 rounded-lg text-sm shadow-sm transition-colors {$variant}\">\n";
                $code .= "            {$action->label}\n";
                $code .= "          </button>\n";
            }
            $code .= "        </div>\n";
        }
        $code .= "      </header>\n\n";

        $code .= "      <main className=\"grid grid-cols-1 md:grid-cols-12 gap-6\">\n";
        foreach ($screen->components as $cmp) {
            $code .= "        <div className=\"col-span-12\">\n";
            $code .= "          <Card title=\"{$cmp->name}\">\n";
            $code .= "            <p className=\"text-sm text-slate-600\">Component {$cmp->name} [{$cmp->id}]</p>\n";
            $code .= "          </Card>\n";
            $code .= "        </div>\n";
        }
        $code .= "      </main>\n";
        $code .= "    </div>\n";
        $code .= "  );\n";
        $code .= "};\n";

        return $code;
    }

    public function renderComponent(UIComponent $component, DesignSystem $designSystem): string
    {
        return "import React from 'react';\n\n" .
            "export interface {$component->name}Props {\n" .
            "  title?: string;\n" .
            "  children?: React.ReactNode;\n" .
            "}\n\n" .
            "export const {$component->name}: React.FC<{$component->name}Props> = ({ title, children }) => {\n" .
            "  return (\n" .
            "    <div className=\"bg-white rounded-xl border border-slate-200 p-6 shadow-sm\">\n" .
            "      {title && <h3 className=\"text-lg font-semibold text-slate-900 mb-4\">{title}</h3>}\n" .
            "      {children}\n" .
            "    </div>\n" .
            "  );\n" .
            "};\n";
    }

    public function renderNavigation(NavigationTree $navigation, DesignSystem $designSystem): string
    {
        $code = "import React from 'react';\n\n";
        $code .= "export const AppSidebar: React.FC = () => {\n";
        $code .= "  return (\n";
        $code .= "    <aside className=\"w-64 bg-slate-900 text-white min-h-screen p-4 flex flex-col\">\n";
        $code .= "      <div className=\"text-xl font-bold tracking-tight px-3 py-4 border-b border-slate-800\">\n";
        $code .= "        {$navigation->brandTitle}\n";
        $code .= "      </div>\n";
        $code .= "      <nav className=\"mt-6 flex-1 space-y-1\">\n";

        foreach ($navigation->nodes as $node) {
            $code .= "        <a href=\"{$node->route}\" className=\"flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-colors\">\n";
            $code .= "          <span>{$node->label}</span>\n";
            $code .= "        </a>\n";
        }

        $code .= "      </nav>\n";
        $code .= "    </aside>\n";
        $code .= "  );\n";
        $code .= "};\n";

        return $code;
    }

    public function renderDesignSystem(DesignSystem $designSystem): array
    {
        $tokensCss = ":root {\n";
        foreach ($designSystem->globalTokens as $name => $tok) {
            $tokensCss .= "  --{$name}: {$tok->value};\n";
        }
        $tokensCss .= "}\n";

        return [
            'src/theme/tokens.css' => $tokensCss,
        ];
    }

    public function generateProjectUI(
        array $screens,
        NavigationTree $navigation,
        DesignSystem $designSystem,
        string $outputDir,
        bool $dryRun = false
    ): UIGenerationResult {
        $metaManager = new FileMetadataManager($outputDir);
        $files = [];

        // Design system
        foreach ($this->renderDesignSystem($designSystem) as $rel => $c) {
            $files[$rel] = $c;
        }

        // Navigation
        $files['src/components/AppSidebar.tsx'] = $this->renderNavigation($navigation, $designSystem);

        // Core Components
        $files['src/components/Card.tsx'] = $this->renderComponent(new UIComponent('cmp_card', 'Card', \Veltrion\Services\UIUX\Enums\ComponentCategory::LAYOUT), $designSystem);
        $files['src/components/Button.tsx'] = $this->renderComponent(new UIComponent('cmp_btn', 'Button', \Veltrion\Services\UIUX\Enums\ComponentCategory::FORM), $designSystem);
        $files['src/components/DataGrid.tsx'] = $this->renderComponent(new UIComponent('cmp_grid', 'DataGrid', \Veltrion\Services\UIUX\Enums\ComponentCategory::DISPLAY), $designSystem);

        // Screens
        foreach ($screens as $id => $screen) {
            $pascal = str_replace(['.', '-', '_'], '', ucwords($id, '.-_')) . 'Screen';
            $files["src/screens/{$pascal}.tsx"] = $this->renderScreen($screen, $designSystem);
        }

        $diff = $metaManager->computeDiff($outputDir, $files);
        $written = [];

        if (!$dryRun) {
            foreach ($files as $relPath => $content) {
                $full = rtrim($outputDir, '/') . '/' . ltrim($relPath, '/');
                $dir = dirname($full);
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                file_put_contents($full, $content);
                $written[$relPath] = $full;
            }
            $metaManager->recordGeneratedFiles($written);
        }

        return new UIGenerationResult(
            isSuccess: true,
            platform: $this->getPlatformName(),
            outputDirectory: $outputDir,
            generatedFiles: $files,
            diff: $diff,
            logs: ["Generated " . count($files) . " React UI components and screens successfully."]
        );
    }
}
