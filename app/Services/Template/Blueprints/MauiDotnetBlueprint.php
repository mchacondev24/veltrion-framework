<?php
namespace Veltrion\Services\Template\Blueprints;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\CapabilityMatrix;
use Veltrion\Services\Template\DTOs\FeaturePack;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Enums\TemplateStatus;
use Veltrion\Services\Template\Enums\FeaturePackType;

class MauiDotnetBlueprint
{
    public static function create(): TemplateManifest
    {
        $capabilities = new CapabilityMatrix(
            capabilities: [
                'cross_platform' => true,
                'windows' => true,
                'android' => true,
                'ios' => true,
                'mac_catalyst' => true,
                'xaml' => true,
                'mvvm' => true,
                'dependency_injection' => true,
            ],
            constraints: [
                'dotnet_sdk' => '8.0',
                'csharp_version' => '12.0',
            ]
        );

        $authPack = new FeaturePack(
            id: 'maui-auth-service',
            name: '.NET MAUI SecureStorage Auth Pack',
            type: FeaturePackType::AUTH,
            description: 'Provides SecureStorage token persistence and AuthService with CommunityToolkit.Mvvm ObservableObject',
            dependencies: [
                'CommunityToolkit.Mvvm' => '8.2.2'
            ],
            files: [
                'Services/AuthService.cs' => "namespace App.Services;\nusing Microsoft.Maui.Storage;\n\npublic class AuthService {\n    public async Task SaveTokenAsync(string token) => await SecureStorage.Default.SetAsync(\"auth_token\", token);\n    public async Task<string?> GetTokenAsync() => await SecureStorage.Default.GetAsync(\"auth_token\");\n}\n"
            ]
        );

        return new TemplateManifest(
            id: 'tpl-maui-dotnet-v1',
            name: '.NET 8 MAUI Cross-Platform C# Blueprint',
            version: '8.0.100',
            platform: PlatformType::MAUI,
            language: 'C#',
            architecture: 'MVVM Pattern (CommunityToolkit.Mvvm, XAML Views, Dependency Injection)',
            status: TemplateStatus::STABLE,
            capabilities: $capabilities,
            dependencies: [
                'Microsoft.Maui.Controls' => '8.0.3',
                'CommunityToolkit.Mvvm' => '8.2.2',
                'Microsoft.Extensions.Logging.Debug' => '8.0.0'
            ],
            structure: [
                'Models' => 'Data contracts and domain models',
                'ViewModels' => 'MVVM Observable ViewModels',
                'Views' => 'XAML UI Views and code-behinds',
                'Services' => 'Business logic and API clients',
                'Platforms/Android' => 'Android native entry points',
                'Platforms/Windows' => 'Windows App SDK desktop entry points',
                'App.xaml' => 'Global XAML resources and themes',
                'MauiProgram.cs' => 'Dependency Injection host builder entry point',
                'App.csproj' => '.NET MAUI C# Project definition'
            ],
            supportedFeaturePacks: [
                'auth' => $authPack,
            ],
            qualityGates: [
                'dotnet_format' => '0',
            ],
            description: 'Multi-platform application blueprint for Windows, Android, iOS, and macOS built on .NET 8 MAUI, XAML UI, and C# 12 MVVM Toolkit.'
        );
    }
}
