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

class AndroidComposeUIAdapter implements PlatformUIAdapterInterface
{
    public function getPlatformName(): string
    {
        return 'android';
    }

    public function getSupportedExtensions(): array
    {
        return ['kt'];
    }

    public function renderScreen(ScreenModel $screen, DesignSystem $designSystem, ?Theme $theme = null): string
    {
        $pascalName = str_replace(['.', '-', '_'], '', ucwords($screen->id, '.-_')) . 'Screen';

        return "package com.veltrion.app.ui.screens\n\n" .
            "import androidx.compose.foundation.layout.*\n" .
            "import androidx.compose.material3.*\n" .
            "import androidx.compose.runtime.*\n" .
            "import androidx.compose.ui.Modifier\n" .
            "import androidx.compose.ui.unit.dp\n\n" .
            "@OptIn(ExperimentalMaterial3Api::class)\n" .
            "@Composable\n" .
            "fun {$pascalName}() {\n" .
            "    Scaffold(\n" .
            "        topBar = {\n" .
            "            TopAppBar(\n" .
            "                title = { Text(\"{$screen->title}\") }\n" .
            "            )\n" .
            "        }\n" .
            "    ) { innerPadding ->\n" .
            "        Column(\n" .
            "            modifier = Modifier\n" .
            "                .padding(innerPadding)\n" .
            "                .padding(16.dp)\n" .
            "                .fillMaxSize()\n" .
            "        ) {\n" .
            "            Text(\n" .
            "                text = \"{$screen->purpose}\",\n" .
            "                style = MaterialTheme.typography.bodyMedium\n" .
            "            )\n" .
            "            Spacer(modifier = Modifier.height(16.dp))\n" .
            "            Card(\n" .
            "                modifier = Modifier.fillMaxWidth()\n" .
            "            ) {\n" .
            "                Box(modifier = Modifier.padding(16.dp)) {\n" .
            "                    Text(\"{$screen->title} Screen Content\")\n" .
            "                }\n" .
            "            }\n" .
            "        }\n" .
            "    }\n" .
            "}\n";
    }

    public function renderComponent(UIComponent $component, DesignSystem $designSystem): string
    {
        return "package com.veltrion.app.ui.components\n\n" .
            "import androidx.compose.material3.*\n" .
            "import androidx.compose.runtime.Composable\n" .
            "import androidx.compose.foundation.layout.padding\n" .
            "import androidx.compose.ui.Modifier\n" .
            "import androidx.compose.ui.unit.dp\n\n" .
            "@Composable\n" .
            "fun {$component->name}Card(content: @Composable () -> Unit) {\n" .
            "    Card(modifier = Modifier.padding(8.dp)) {\n" .
            "        content()\n" .
            "    }\n" .
            "}\n";
    }

    public function renderNavigation(NavigationTree $navigation, DesignSystem $designSystem): string
    {
        return "package com.veltrion.app.ui.navigation\n\n" .
            "import androidx.compose.material3.*\n" .
            "import androidx.compose.runtime.Composable\n\n" .
            "@Composable\n" .
            "fun AppBottomBar() {\n" .
            "    NavigationBar {\n" .
            "        NavigationBarItem(\n" .
            "            selected = true,\n" .
            "            onClick = {},\n" .
            "            icon = {},\n" .
            "            label = { Text(\"Dashboard\") }\n" .
            "        )\n" .
            "    }\n" .
            "}\n";
    }

    public function renderDesignSystem(DesignSystem $designSystem): array
    {
        return [
            'app/src/main/java/com/veltrion/app/ui/theme/Color.kt' => "package com.veltrion.app.ui.theme\n\n" .
                "import androidx.compose.ui.graphics.Color\n\n" .
                "val PrimaryColor = Color(0xFF" . ltrim($designSystem->branding->primaryColor, '#') . ")\n" .
                "val SecondaryColor = Color(0xFF" . ltrim($designSystem->branding->secondaryColor, '#') . ")\n"
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

        $files['app/src/main/java/com/veltrion/app/ui/navigation/AppNavigation.kt'] = $this->renderNavigation($navigation, $designSystem);

        foreach ($screens as $id => $screen) {
            $pascal = str_replace(['.', '-', '_'], '', ucwords($id, '.-_')) . 'Screen';
            $files["app/src/main/java/com/veltrion/app/ui/screens/{$pascal}.kt"] = $this->renderScreen($screen, $designSystem);
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
            logs: ["Generated " . count($files) . " Android Jetpack Compose composables successfully."]
        );
    }
}
