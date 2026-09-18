<?php
namespace Veltrion\Services\Template\Blueprints;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\CapabilityMatrix;
use Veltrion\Services\Template\DTOs\FeaturePack;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Enums\TemplateStatus;
use Veltrion\Services\Template\Enums\FeaturePackType;

class FlutterDartBlueprint
{
    public static function create(): TemplateManifest
    {
        $capabilities = new CapabilityMatrix(
            capabilities: [
                'cross_platform' => true,
                'flutter_web' => true,
                'flutter_mobile' => true,
                'flutter_desktop' => true,
                'state_management' => true,
                'material_design_3' => true,
            ],
            constraints: [
                'flutter_sdk' => '>=3.16.0',
                'dart_sdk' => '>=3.2.0 <4.0.0',
            ]
        );

        $authPack = new FeaturePack(
            id: 'flutter-riverpod-auth',
            name: 'Flutter Riverpod Authentication Pack',
            type: FeaturePackType::AUTH,
            description: 'Provides Riverpod StateNotifier for Authentication state and SecureStorage persistence',
            dependencies: [
                'flutter_riverpod' => '^2.4.9',
                'flutter_secure_storage' => '^9.0.0'
            ],
            files: [
                'lib/features/auth/providers/auth_provider.dart' => "import 'package:flutter_riverpod/flutter_riverpod.dart';\n\nclass AuthState {\n  final bool isAuthenticated;\n  AuthState({required this.isAuthenticated});\n}\n\nclass AuthNotifier extends StateNotifier<AuthState> {\n  AuthNotifier(): super(AuthState(isAuthenticated: false));\n  void login() => state = AuthState(isAuthenticated: true);\n  void logout() => state = AuthState(isAuthenticated: false);\n}\n\nfinal authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) => AuthNotifier());\n"
            ]
        );

        return new TemplateManifest(
            id: 'tpl-flutter-dart-v1',
            name: 'Flutter Multi-Platform Feature-First Architecture Blueprint',
            version: '3.16.0',
            platform: PlatformType::FLUTTER,
            language: 'Dart',
            architecture: 'Feature-First Layered Architecture (Riverpod / BLoC, Freezed, GoRouter)',
            status: TemplateStatus::STABLE,
            capabilities: $capabilities,
            dependencies: [
                'flutter' => 'sdk: flutter',
                'flutter_riverpod' => '^2.4.9',
                'go_router' => '^13.1.0',
                'cupertino_icons' => '^1.0.6'
            ],
            structure: [
                'lib/core/theme' => 'Material Design 3 App Theme and styling',
                'lib/core/router' => 'GoRouter declarative navigation definition',
                'lib/features' => 'Feature-first modules (data, domain, presentation)',
                'lib/main.dart' => 'Flutter runApp entry point',
                'pubspec.yaml' => 'Flutter package definition and assets'
            ],
            supportedFeaturePacks: [
                'auth' => $authPack,
            ],
            qualityGates: [
                'flutter_analyze' => 'PASS',
            ],
            description: 'Multi-platform Flutter template designed for iOS, Android, Web, and Desktop with Riverpod state management and Material Design 3.'
        );
    }
}
