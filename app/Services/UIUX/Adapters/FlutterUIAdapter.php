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

class FlutterUIAdapter implements PlatformUIAdapterInterface
{
    public function getPlatformName(): string
    {
        return 'flutter';
    }

    public function getSupportedExtensions(): array
    {
        return ['dart'];
    }

    public function renderScreen(ScreenModel $screen, DesignSystem $designSystem, ?Theme $theme = null): string
    {
        $pascalName = str_replace(['.', '-', '_'], '', ucwords($screen->id, '.-_')) . 'Screen';

        return "import 'package:flutter/material.dart';\n\n" .
            "class {$pascalName} extends StatelessWidget {\n" .
            "  const {$pascalName}({super.key});\n\n" .
            "  @override\n" .
            "  Widget build(BuildContext context) {\n" .
            "    final theme = Theme.of(context);\n" .
            "    return Scaffold(\n" .
            "      appBar: AppBar(\n" .
            "        title: Text('{$screen->title}'),\n" .
            "      ),\n" .
            "      body: SingleChildScrollView(\n" .
            "        padding: const EdgeInsets.all(16.0),\n" .
            "        child: Column(\n" .
            "          crossAxisAlignment: CrossAxisAlignment.start,\n" .
            "          children: [\n" .
            "            Text('{$screen->purpose}', style: theme.textTheme.bodyMedium),\n" .
            "            const SizedBox(height: 16),\n" .
            "            Card(\n" .
            "              child: Padding(\n" .
            "                padding: const EdgeInsets.all(16.0),\n" .
            "                child: Text('{$screen->title} Content'),\n" .
            "              ),\n" .
            "            ),\n" .
            "          ],\n" .
            "        ),\n" .
            "      ),\n" .
            "    );\n" .
            "  }\n" .
            "}\n";
    }

    public function renderComponent(UIComponent $component, DesignSystem $designSystem): string
    {
        return "import 'package:flutter/material.dart';\n\n" .
            "class {$component->name}Widget extends StatelessWidget {\n" .
            "  final Widget? child;\n" .
            "  const {$component->name}Widget({super.key, this.child});\n\n" .
            "  @override\n" .
            "  Widget build(BuildContext context) {\n" .
            "    return Card(\n" .
            "      margin: const EdgeInsets.symmetric(vertical: 8.0),\n" .
            "      child: Padding(\n" .
            "        padding: const EdgeInsets.all(16.0),\n" .
            "        child: child ?? const SizedBox.shrink(),\n" .
            "      ),\n" .
            "    );\n" .
            "  }\n" .
            "}\n";
    }

    public function renderNavigation(NavigationTree $navigation, DesignSystem $designSystem): string
    {
        return "import 'package:flutter/material.dart';\n\n" .
            "class AppNavigationDrawer extends StatelessWidget {\n" .
            "  const AppNavigationDrawer({super.key});\n\n" .
            "  @override\n" .
            "  Widget build(BuildContext context) {\n" .
            "    return Drawer(\n" .
            "      child: ListView(\n" .
            "        padding: EdgeInsets.zero,\n" .
            "        children: [\n" .
            "          const DrawerHeader(\n" .
            "            decoration: BoxDecoration(color: Colors.blue),\n" .
            "            child: Text('{$navigation->brandTitle}', style: TextStyle(color: Colors.white, fontSize: 20)),\n" .
            "          ),\n" .
            "          ListTile(\n" .
            "            leading: const Icon(Icons.dashboard),\n" .
            "            title: const Text('Dashboard'),\n" .
            "            onTap: () => Navigator.pop(context),\n" .
            "          ),\n" .
            "        ],\n" .
            "      ),\n" .
            "    );\n" .
            "  }\n" .
            "}\n";
    }

    public function renderDesignSystem(DesignSystem $designSystem): array
    {
        return [
            'lib/theme/app_theme.dart' => "import 'package:flutter/material.dart';\n\n" .
                "class AppTheme {\n" .
                "  static final light = ThemeData(\n" .
                "    colorScheme: ColorScheme.fromSeed(seedColor: Color(0xFF" . ltrim($designSystem->branding->primaryColor, '#') . ")),\n" .
                "    useMaterial3: true,\n" .
                "  );\n" .
                "}\n"
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

        $files['lib/widgets/app_drawer.dart'] = $this->renderNavigation($navigation, $designSystem);

        foreach ($screens as $id => $screen) {
            $snake = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $id));
            $files["lib/screens/{$snake}_screen.dart"] = $this->renderScreen($screen, $designSystem);
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
            logs: ["Generated " . count($files) . " Flutter Material 3 widgets successfully."]
        );
    }
}
