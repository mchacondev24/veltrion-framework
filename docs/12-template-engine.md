# Universal Project Template Engine (Phase 12)

## Overview
The Universal Project Template Engine extends the Veltrion Multi-Platform Application Factory by providing specialized, versioned, and configurable software project blueprints for 6 target platforms without duplicating core framework logic.

## Supported Target Platforms
1. **PHP API / Clean Architecture**: PSR-11 container autowiring, PDO database pool, Clean Architecture layers.
2. **Angular Web Application**: Angular 17+ standalone components, reactive signals, control flow, RxJS.
3. **React Web Application**: React 18, Vite build system, TypeScript, Tailwind CSS, Lucide icons.
4. **Android Native**: Kotlin, Jetpack Compose, ViewModel, Material 3, Coroutines/Flow, MVVM.
5. **MAUI Cross-Platform**: .NET 8 C# 12, XAML UI, CommunityToolkit.Mvvm, multi-target (Windows, Android, iOS, macOS).
6. **Flutter Multi-Platform**: Dart, Riverpod state management, GoRouter, Material Design 3.

## Architectural Architecture & Components

```
app/Services/Template/
├── Contracts/
│   ├── TemplateRegistryInterface.php
│   ├── TemplateResolverInterface.php
│   └── TemplateGeneratorInterface.php
├── DTOs/
│   ├── CapabilityMatrix.php
│   ├── FeaturePack.php
│   ├── TemplateManifest.php
│   ├── TemplateResolutionResult.php
│   └── GenerationResult.php
├── Enums/
│   ├── FeaturePackType.php
│   ├── PlatformType.php
│   └── TemplateStatus.php
├── Blueprints/
│   ├── PhpApiCleanArchBlueprint.php
│   ├── AngularWebAppBlueprint.php
│   ├── ReactWebAppBlueprint.php
│   ├── AndroidKotlinBlueprint.php
│   ├── MauiDotnetBlueprint.php
│   └── FlutterDartBlueprint.php
├── TemplateRegistry.php
├── TemplateResolver.php
├── TemplateGenerator.php
└── TemplateEngine.php
```

## CLI Commands
```bash
# List all registered project template blueprints
php cli template:list

# Inspect detailed manifest, capabilities, and feature packs for a template
php cli template:show tpl-android-kotlin-v1

# Resolve matching template based on platform and requirements
php cli template:resolve flutter

# Create and generate a complete project scaffold from a template
php cli template:create angular MyStoreApp
```
