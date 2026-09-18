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

class AngularUIAdapter implements PlatformUIAdapterInterface
{
    public function getPlatformName(): string
    {
        return 'angular';
    }

    public function getSupportedExtensions(): array
    {
        return ['ts', 'html', 'scss'];
    }

    public function renderScreen(ScreenModel $screen, DesignSystem $designSystem, ?Theme $theme = null): string
    {
        $pascalName = str_replace(['.', '-', '_'], '', ucwords($screen->id, '.-_')) . 'Component';
        $kebabName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $screen->id));

        return "import { Component, signal } from '@angular/core';\n" .
            "import { CommonModule } from '@angular/common';\n" .
            "import { RouterModule } from '@angular/router';\n\n" .
            "@Component({\n" .
            "  selector: 'app-{$kebabName}',\n" .
            "  standalone: true,\n" .
            "  imports: [CommonModule, RouterModule],\n" .
            "  template: `\n" .
            "    <div class=\"p-6 space-y-6\">\n" .
            "      <header class=\"flex items-center justify-between border-b pb-4\">\n" .
            "        <h1 class=\"text-2xl font-bold text-gray-900\">{$screen->title}</h1>\n" .
            "      </header>\n" .
            "      <main class=\"grid grid-cols-12 gap-6\">\n" .
            "        <div class=\"col-span-12 bg-white rounded-lg shadow-sm border p-6\">\n" .
            "          <p class=\"text-gray-600\">{$screen->purpose}</p>\n" .
            "        </div>\n" .
            "      </main>\n" .
            "    </div>\n" .
            "  `\n" .
            "})\n" .
            "export class {$pascalName} {\n" .
            "  loading = signal<boolean>(false);\n" .
            "}\n";
    }

    public function renderComponent(UIComponent $component, DesignSystem $designSystem): string
    {
        $kebab = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $component->name));
        return "import { Component, Input } from '@angular/core';\n" .
            "import { CommonModule } from '@angular/common';\n\n" .
            "@Component({\n" .
            "  selector: 'app-{$kebab}',\n" .
            "  standalone: true,\n" .
            "  imports: [CommonModule],\n" .
            "  template: `\n" .
            "    <div class=\"bg-white rounded-lg border border-slate-200 p-4 shadow-sm\">\n" .
            "      <ng-content></ng-content>\n" .
            "    </div>\n" .
            "  `\n" .
            "})\n" .
            "export class {$component->name}Component {}\n";
    }

    public function renderNavigation(NavigationTree $navigation, DesignSystem $designSystem): string
    {
        return "import { Component } from '@angular/core';\n" .
            "import { CommonModule } from '@angular/common';\n" .
            "import { RouterModule } from '@angular/router';\n\n" .
            "@Component({\n" .
            "  selector: 'app-sidebar',\n" .
            "  standalone: true,\n" .
            "  imports: [CommonModule, RouterModule],\n" .
            "  template: `\n" .
            "    <aside class=\"w-64 bg-slate-900 text-white min-h-screen p-4\">\n" .
            "      <div class=\"font-bold text-xl mb-6\">{$navigation->brandTitle}</div>\n" .
            "      <nav class=\"space-y-1\">\n" .
            "        <a *ngFor=\"let n of navItems\" [routerLink]=\"n.route\" class=\"block px-3 py-2 rounded hover:bg-slate-800\">\n" .
            "          {{ n.label }}\n" .
            "        </a>\n" .
            "      </nav>\n" .
            "    </aside>\n" .
            "  `\n" .
            "})\n" .
            "export class AppSidebarComponent {\n" .
            "  navItems = " . json_encode($navigation->nodes, JSON_PRETTY_PRINT) . ";\n" .
            "}\n";
    }

    public function renderDesignSystem(DesignSystem $designSystem): array
    {
        return [
            'src/styles/tokens.scss' => "\$primary-color: {$designSystem->branding->primaryColor};\n\$font-family: {$designSystem->branding->fontFamily};\n",
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

        foreach ($this->renderDesignSystem($designSystem) as $rel => $c) {
            $files[$rel] = $c;
        }

        $files['src/app/components/sidebar/sidebar.component.ts'] = $this->renderNavigation($navigation, $designSystem);

        foreach ($screens as $id => $screen) {
            $kebab = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $id));
            $files["src/app/screens/{$kebab}/{$kebab}.component.ts"] = $this->renderScreen($screen, $designSystem);
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
            logs: ["Generated " . count($files) . " Angular Standalone components successfully."]
        );
    }
}
