<?php
namespace Veltrion\Services\Template;

use Veltrion\Services\Template\Contracts\TemplateGeneratorInterface;
use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\GenerationResult;
use Veltrion\Services\Template\DTOs\FeaturePack;

class TemplateGenerator implements TemplateGeneratorInterface
{
    public function generate(
        TemplateManifest $manifest,
        string $outputPath,
        array $variables = [],
        array $featurePackIds = []
    ): GenerationResult {
        $logs = [];
        $generatedFiles = [];
        $appliedPacks = [];
        $mergedDependencies = $manifest->dependencies;

        $logs[] = "Initializing Template Engine generation for template [{$manifest->id}] at [{$outputPath}]";

        if (!is_dir($outputPath)) {
            mkdir($outputPath, 0777, true);
            $logs[] = "Created root destination directory: {$outputPath}";
        }

        // Apply default placeholder values
        $defaultVars = [
            'appName' => $variables['appName'] ?? 'VeltrionApp',
            'namespace' => $variables['namespace'] ?? 'App',
            'author' => $variables['author'] ?? 'Veltrion Developer',
            'year' => date('Y'),
            'templateVersion' => $manifest->version,
            'platform' => $manifest->platform->value,
        ];
        $vars = array_merge($defaultVars, $variables);

        // Generate base scaffold structure
        foreach ($manifest->structure as $relPath => $desc) {
            $fullPath = $outputPath . '/' . $relPath;

            // If path appears to be a directory (no file extension)
            if (!str_contains(basename($relPath), '.')) {
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                }
                $logs[] = "Created directory structure: {$relPath}";
            } else {
                $dir = dirname($fullPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $content = $this->generateDefaultFileContent($relPath, $manifest, $vars, $desc);
                file_put_contents($fullPath, $content);
                $generatedFiles[] = $relPath;
                $logs[] = "Generated file: {$relPath}";
            }
        }

        // Overlay feature packs
        foreach ($featurePackIds as $packKey) {
            if (isset($manifest->supportedFeaturePacks[$packKey])) {
                $pack = $manifest->supportedFeaturePacks[$packKey];
                if ($pack instanceof FeaturePack) {
                    $appliedPacks[] = $pack->id;
                    $logs[] = "Overlaying Feature Pack: {$pack->name} ({$pack->id})";

                    // Merge dependencies
                    foreach ($pack->dependencies as $depName => $depVer) {
                        $mergedDependencies[$depName] = $depVer;
                    }

                    // Overlay files
                    foreach ($pack->files as $packRelPath => $rawContent) {
                        $fullPath = $outputPath . '/' . $packRelPath;
                        $dir = dirname($fullPath);
                        if (!is_dir($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $processedContent = $this->replaceVariables($rawContent, $vars);
                        file_put_contents($fullPath, $processedContent);
                        if (!in_array($packRelPath, $generatedFiles)) {
                            $generatedFiles[] = $packRelPath;
                        }
                        $logs[] = "Injected Feature Pack file: {$packRelPath}";
                    }
                }
            }
        }

        // Write template manifest descriptor file into project
        $manifestPath = $outputPath . '/template-manifest.json';
        file_put_contents(
            $manifestPath,
            json_encode([
                'manifest' => $manifest->toArray(),
                'variables' => $vars,
                'applied_feature_packs' => $appliedPacks,
                'final_dependencies' => $mergedDependencies,
                'generated_at' => date('Y-m-d H:i:s'),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        $generatedFiles[] = 'template-manifest.json';

        $logs[] = "Template generation completed successfully. Total files: " . count($generatedFiles);

        return new GenerationResult(
            templateId: $manifest->id,
            outputPath: $outputPath,
            generatedFiles: $generatedFiles,
            appliedFeaturePacks: $appliedPacks,
            mergedDependencies: $mergedDependencies,
            logs: $logs
        );
    }

    private function generateDefaultFileContent(
        string $relPath,
        TemplateManifest $manifest,
        array $vars,
        string $description
    ): string {
        $ext = pathinfo($relPath, PATHINFO_EXTENSION);
        $appName = $vars['appName'];

        if ($relPath === 'composer.json') {
            return json_encode([
                'name' => strtolower($vars['namespace']) . '/' . strtolower($appName),
                'description' => "Generated from template {$manifest->name}",
                'type' => 'project',
                'require' => $manifest->dependencies,
                'autoload' => [
                    'psr-4' => [
                        $vars['namespace'] . '\\' => 'app/'
                    ]
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        if ($relPath === 'package.json') {
            return json_encode([
                'name' => strtolower($appName),
                'version' => '1.0.0',
                'description' => "Generated from template {$manifest->name}",
                'scripts' => [
                    'dev' => 'vite',
                    'build' => 'tsc && vite build',
                    'preview' => 'vite preview'
                ],
                'dependencies' => $manifest->dependencies
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        if ($ext === 'php') {
            return "<?php\n// Generated file for {$appName}\n// Template: {$manifest->id}\n// Description: {$description}\n\nnamespace {$vars['namespace']};\n\nclass " . ucfirst(basename($relPath, '.php')) . " {\n    public function __construct() {}\n}\n";
        }

        if ($ext === 'ts' || $ext === 'tsx') {
            return "// Generated file for {$appName}\n// Template: {$manifest->id}\n// Description: {$description}\n\nexport const " . ucfirst(basename($relPath, '.' . $ext)) . " = () => {\n  return '{$appName} Module';\n};\n";
        }

        if ($ext === 'kt') {
            return "package com.example." . strtolower($appName) . "\n\n// Generated Kotlin class for {$appName}\nclass " . ucfirst(basename($relPath, '.kt')) . " {\n}\n";
        }

        if ($ext === 'cs') {
            return "namespace {$appName}\n{\n    // Generated C# class\n    public class " . ucfirst(basename($relPath, '.cs')) . "\n    {\n    }\n}\n";
        }

        if ($ext === 'dart') {
            return "// Generated Dart module for {$appName}\nclass " . ucfirst(basename($relPath, '.dart')) . " {\n}\n";
        }

        return "/* {$appName} - {$description} */\n";
    }

    private function replaceVariables(string $templateText, array $vars): string
    {
        foreach ($vars as $key => $val) {
            if (is_scalar($val)) {
                $templateText = str_replace(['{{ ' . $key . ' }}', '{{' . $key . '}}'], (string)$val, $templateText);
            }
        }
        return $templateText;
    }
}
