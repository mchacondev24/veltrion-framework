<?php
namespace Veltrion\Services\Template\Blueprints;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\CapabilityMatrix;
use Veltrion\Services\Template\DTOs\FeaturePack;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Enums\TemplateStatus;
use Veltrion\Services\Template\Enums\FeaturePackType;

class AngularWebAppBlueprint
{
    public static function create(): TemplateManifest
    {
        $capabilities = new CapabilityMatrix(
            capabilities: [
                'spa' => true,
                'reactive_signals' => true,
                'routing' => true,
                'pwa' => true,
                'i18n' => true,
                'forms' => true,
            ],
            constraints: [
                'node_version' => '>=18.0',
                'typescript' => '^5.2',
                'angular_cli' => '^17.0',
            ]
        );

        $authPack = new FeaturePack(
            id: 'angular-auth-guard',
            name: 'Angular Standalone Auth Guard & AuthService Pack',
            type: FeaturePackType::AUTH,
            description: 'Provides Injectable AuthService with BehaviorSubject/Signals and Functional CanActivateFn AuthGuards',
            dependencies: ['@angular/common' => '^17.0.0'],
            files: [
                'src/app/core/auth/auth.service.ts' => "import { Injectable, signal } from '@angular/core';\n\n@Injectable({ providedIn: 'root' })\nexport class AuthService {\n  readonly isAuthenticated = signal<boolean>(false);\n  login() { this.isAuthenticated.set(true); }\n  logout() { this.isAuthenticated.set(false); }\n}\n",
                'src/app/core/auth/auth.guard.ts' => "import { inject } from '@angular/core';\nimport { CanActivateFn, Router } from '@angular/router';\nimport { AuthService } from './auth.service';\n\nexport const authGuard: CanActivateFn = () => {\n  const auth = inject(AuthService);\n  const router = inject(Router);\n  return auth.isAuthenticated() ? true : router.createUrlTree(['/login']);\n};\n"
            ]
        );

        return new TemplateManifest(
            id: 'tpl-angular-web-app-v1',
            name: 'Angular Enterprise Standalone Application Blueprint',
            version: '17.1.0',
            platform: PlatformType::ANGULAR,
            language: 'TypeScript',
            architecture: 'Component Architecture (Standalone Components, Signals, RxJS, Core/Features/Shared)',
            status: TemplateStatus::STABLE,
            capabilities: $capabilities,
            dependencies: [
                '@angular/core' => '^17.0.0',
                '@angular/common' => '^17.0.0',
                '@angular/router' => '^17.0.0',
                'rxjs' => '~7.8.0',
                'typescript' => '~5.2.2'
            ],
            structure: [
                'src/app/core' => 'Singleton services, interceptors, guards',
                'src/app/features' => 'Feature modules with standalone page components',
                'src/app/shared' => 'Reusable UI elements, directives, pipes',
                'src/app/app.routes.ts' => 'Lazy-loaded route mapping',
                'src/main.ts' => 'Bootstrap application entry point',
                'angular.json' => 'Angular CLI build configuration'
            ],
            supportedFeaturePacks: [
                'auth' => $authPack,
            ],
            qualityGates: [
                'karma_coverage' => '80%',
                'eslint_max_warnings' => '0',
            ],
            description: 'Modern Angular 17+ template leveraging Standalone Components, Reactive Signals, Control Flow syntax, and strict TypeScript checks.'
        );
    }
}
