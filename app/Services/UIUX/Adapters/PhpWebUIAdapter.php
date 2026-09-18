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

class PhpWebUIAdapter implements PlatformUIAdapterInterface
{
    public function getPlatformName(): string
    {
        return 'php_web';
    }

    public function getSupportedExtensions(): array
    {
        return ['php', 'html', 'css'];
    }

    public function renderScreen(ScreenModel $screen, DesignSystem $designSystem, ?Theme $theme = null): string
    {
        $code = "<?php\n";
        $code .= "/** @var string \$title */\n";
        $code .= "\$title = '{$screen->title}';\n";
        $code .= "?>\n";
        $code .= "<!DOCTYPE html>\n";
        $code .= "<html lang=\"en\">\n";
        $code .= "<head>\n";
        $code .= "    <meta charset=\"UTF-8\">\n";
        $code .= "    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
        $code .= "    <title><?= htmlspecialchars(\$title) ?> | {$designSystem->branding->appName}</title>\n";
        $code .= "    <script src=\"https://cdn.tailwindcss.com\"></script>\n";
        $code .= "</head>\n";
        $code .= "<body class=\"bg-slate-50 text-slate-900 font-sans min-h-screen flex\">\n";
        $code .= "    <?php include __DIR__ . '/../partials/sidebar.php'; ?>\n";
        $code .= "    <div class=\"flex-1 flex flex-col\">\n";
        $code .= "        <main class=\"p-6 md:p-8 space-y-6\">\n";
        $code .= "            <div class=\"border-b border-slate-200 pb-4\">\n";
        $code .= "                <h1 class=\"text-2xl font-bold text-slate-900\"><?= htmlspecialchars(\$title) ?></h1>\n";
        $code .= "                <p class=\"text-sm text-slate-500 mt-1\">{$screen->purpose}</p>\n";
        $code .= "            </div>\n";
        $code .= "            <div class=\"bg-white rounded-xl border border-slate-200 p-6 shadow-sm\">\n";
        $code .= "                <p class=\"text-slate-600\">Screen content for {$screen->title}</p>\n";
        $code .= "            </div>\n";
        $code .= "        </main>\n";
        $code .= "    </div>\n";
        $code .= "</body>\n";
        $code .= "</html>\n";

        return $code;
    }

    public function renderComponent(UIComponent $component, DesignSystem $designSystem): string
    {
        return "<div class=\"bg-white rounded-lg border border-slate-200 p-4 shadow-sm\">\n" .
            "    <h3 class=\"text-md font-semibold text-slate-900 mb-2\">{$component->name}</h3>\n" .
            "    <div class=\"component-body\">\n" .
            "        <!-- Content -->\n" .
            "    </div>\n" .
            "</div>\n";
    }

    public function renderNavigation(NavigationTree $navigation, DesignSystem $designSystem): string
    {
        $code = "<aside class=\"w-64 bg-slate-900 text-white min-h-screen p-4 flex flex-col\">\n";
        $code .= "    <div class=\"text-xl font-bold tracking-tight px-3 py-4 border-b border-slate-800\">\n";
        $code .= "        <?= htmlspecialchars('{$navigation->brandTitle}') ?>\n";
        $code .= "    </div>\n";
        $code .= "    <nav class=\"mt-6 flex-1 space-y-1\">\n";

        foreach ($navigation->nodes as $node) {
            $code .= "        <a href=\"{$node->route}\" class=\"flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-colors\">\n";
            $code .= "            <span>{$node->label}</span>\n";
            $code .= "        </a>\n";
        }

        $code .= "    </nav>\n";
        $code .= "</aside>\n";

        return $code;
    }

    public function renderDesignSystem(DesignSystem $designSystem): array
    {
        return [
            'public/css/tokens.css' => ":root {\n  --primary: {$designSystem->branding->primaryColor};\n}\n",
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

        $files['resources/views/partials/sidebar.php'] = $this->renderNavigation($navigation, $designSystem);

        foreach ($screens as $id => $screen) {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $id));
            $files["resources/views/screens/{$slug}.php"] = $this->renderScreen($screen, $designSystem);
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
            logs: ["Generated " . count($files) . " PHP Web views successfully."]
        );
    }
}
