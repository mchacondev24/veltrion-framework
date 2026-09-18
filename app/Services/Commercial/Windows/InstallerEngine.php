<?php

namespace Veltrion\Services\Commercial\Windows;

class InstallerEngine
{
    private string $projectRoot;

    public function __construct(?string $projectRoot = null)
    {
        $this->projectRoot = $projectRoot ?? __DIR__ . '/../../../../';
    }

    public function generateInstallerDefinition(string $productName, string $version, string $outputDir): array
    {
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $innoScript = "; Veltrion Framework Commercial Installer Script (Inno Setup)\n" .
            "[Setup]\n" .
            "AppId={{VELTRION-{$productName}-{$version}}}\n" .
            "AppName={$productName}\n" .
            "AppVersion={$version}\n" .
            "AppPublisher=Veltrion Commercial Systems\n" .
            "DefaultDirName={autopf}\\{$productName}\n" .
            "DefaultGroupName={$productName}\n" .
            "OutputDir={$outputDir}\n" .
            "OutputBaseFilename={$productName}-Setup-v{$version}\n" .
            "Compression=lzma2/max\n" .
            "SolidCompression=yes\n" .
            "ArchitecturesInstallIn64BitMode=x64\n\n" .
            "[Files]\n" .
            "Source: \"*\"; DestDir: \"{app}\"; Flags: ignoreversion recursesubdirs createallsubdirs\n\n" .
            "[Icons]\n" .
            "Name: \"{group}\\{$productName}\"; Filename: \"{app}\\launch.bat\"\n" .
            "Name: \"{autodesktop}\\{$productName}\"; Filename: \"{app}\\launch.bat\"; Tasks: desktopicon\n\n" .
            "[Tasks]\n" .
            "Name: \"desktopicon\"; Description: \"{cm:CreateDesktopIcon}\"; GroupDescription: \"{cm:AdditionalIcons}\"; Flags: unchecked\n";

        $issPath = $outputDir . '/installer.iss';
        file_put_contents($issPath, $innoScript);

        $manifest = [
            'product' => $productName,
            'version' => $version,
            'publisher' => 'Veltrion Commercial Systems',
            'inno_setup_script' => $issPath,
            'default_install_path' => "C:\\Program Files\\{$productName}",
            'shortcuts' => ['Desktop', 'StartMenu'],
            'uninstaller_generated' => true,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        $manifestPath = $outputDir . '/installer-manifest.json';
        file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return [
            'product' => $productName,
            'version' => $version,
            'inno_script' => $issPath,
            'manifest' => $manifestPath,
        ];
    }
}
