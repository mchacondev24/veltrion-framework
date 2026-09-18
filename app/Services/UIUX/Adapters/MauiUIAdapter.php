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

class MauiUIAdapter implements PlatformUIAdapterInterface
{
    public function getPlatformName(): string
    {
        return 'maui';
    }

    public function getSupportedExtensions(): array
    {
        return ['xaml', 'cs'];
    }

    public function renderScreen(ScreenModel $screen, DesignSystem $designSystem, ?Theme $theme = null): string
    {
        $pascalName = str_replace(['.', '-', '_'], '', ucwords($screen->id, '.-_')) . 'Page';

        return "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n" .
            "<ContentPage xmlns=\"http://schemas.microsoft.com/dotnet/2021/maui\"\n" .
            "             xmlns:x=\"http://schemas.microsoft.com/winfx/2009/xaml\"\n" .
            "             x:Class=\"Veltrion.App.Views.{$pascalName}\"\n" .
            "             Title=\"{$screen->title}\">\n" .
            "    <ScrollView>\n" .
            "        <VerticalStackLayout Padding=\"20\" Spacing=\"15\">\n" .
            "            <Label Text=\"{$screen->title}\" FontSize=\"24\" FontAttributes=\"Bold\" />\n" .
            "            <Label Text=\"{$screen->purpose}\" FontSize=\"14\" TextColor=\"Gray\" />\n" .
            "            <Border Stroke=\"#E5E7EB\" StrokeThickness=\"1\" Padding=\"16\" Background=\"White\">\n" .
            "                <Label Text=\"{$screen->title} Content Container\" />\n" .
            "            </Border>\n" .
            "        </VerticalStackLayout>\n" .
            "    </ScrollView>\n" .
            "</ContentPage>\n";
    }

    public function renderComponent(UIComponent $component, DesignSystem $designSystem): string
    {
        return "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n" .
            "<ContentView xmlns=\"http://schemas.microsoft.com/dotnet/2021/maui\"\n" .
            "             xmlns:x=\"http://schemas.microsoft.com/winfx/2009/xaml\"\n" .
            "             x:Class=\"Veltrion.App.Controls.{$component->name}Control\">\n" .
            "    <Border Stroke=\"#E5E7EB\" StrokeThickness=\"1\" Padding=\"12\">\n" .
            "        <Label Text=\"{$component->name} Control\" />\n" .
            "    </Border>\n" .
            "</ContentView>\n";
    }

    public function renderNavigation(NavigationTree $navigation, DesignSystem $designSystem): string
    {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
            "<Shell\n" .
            "    x:Class=\"Veltrion.App.AppShell\"\n" .
            "    xmlns=\"http://schemas.microsoft.com/dotnet/2021/maui\"\n" .
            "    xmlns:x=\"http://schemas.microsoft.com/winfx/2009/xaml\"\n" .
            "    Title=\"{$navigation->brandTitle}\">\n" .
            "    <TabBar>\n" .
            "        <Tab Title=\"Dashboard\">\n" .
            "            <ShellContent ContentTemplate=\"{DataTemplate views:DashboardPage}\" />\n" .
            "        </Tab>\n" .
            "    </TabBar>\n" .
            "</Shell>\n";
    }

    public function renderDesignSystem(DesignSystem $designSystem): array
    {
        return [
            'Resources/Styles/Colors.xaml' => "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n" .
                "<ResourceDictionary xmlns=\"http://schemas.microsoft.com/dotnet/2021/maui\"\n" .
                "                    xmlns:x=\"http://schemas.microsoft.com/winfx/2009/xaml\">\n" .
                "    <Color x:Key=\"PrimaryColor\">{$designSystem->branding->primaryColor}</Color>\n" .
                "    <Color x:Key=\"SecondaryColor\">{$designSystem->branding->secondaryColor}</Color>\n" .
                "</ResourceDictionary>\n"
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

        $files['AppShell.xaml'] = $this->renderNavigation($navigation, $designSystem);

        foreach ($screens as $id => $screen) {
            $pascal = str_replace(['.', '-', '_'], '', ucwords($id, '.-_')) . 'Page';
            $files["Views/{$pascal}.xaml"] = $this->renderScreen($screen, $designSystem);
            $files["Views/{$pascal}.xaml.cs"] = "namespace Veltrion.App.Views;\n\npublic partial class {$pascal} : ContentPage {\n    public {$pascal}() {\n        InitializeComponent();\n    }\n}\n";
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
            logs: ["Generated " . count($files) . " .NET MAUI XAML views successfully."]
        );
    }
}
