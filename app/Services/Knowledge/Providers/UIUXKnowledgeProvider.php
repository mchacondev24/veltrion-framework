<?php

namespace Veltrion\Services\Knowledge\Providers;

use Veltrion\Services\Knowledge\Contracts\KnowledgeProviderInterface;
use Veltrion\Services\UIUX\Components\ComponentRegistry;
use Veltrion\Services\UIUX\DesignSystem\DesignTokensFactory;
use Veltrion\Services\UIUX\DTOs\BrandingConfig;

class UIUXKnowledgeProvider implements KnowledgeProviderInterface
{
    private ComponentRegistry $components;
    private DesignTokensFactory $tokensFactory;

    public function __construct()
    {
        $this->components = new ComponentRegistry();
        $this->tokensFactory = new DesignTokensFactory();
    }

    public function getCategory(): string
    {
        return 'uiux';
    }

    public function getKnowledgeData(): array
    {
        $sampleTokens = $this->tokensFactory->createTokens(new BrandingConfig('KnowledgeBase'));
        $componentList = array_map(fn($c) => [
            'name' => $c->name,
            'category' => $c->category->value,
            'properties' => array_keys($c->properties),
            'accessibility_rules' => $c->accessibilityRules,
        ], $this->components->all());

        return [
            'platforms_supported' => [
                'react' => 'React 18 + TypeScript + Tailwind CSS',
                'angular' => 'Angular 17+ Standalone + Signals + RxJS',
                'flutter' => 'Flutter Material 3 + Dart + Riverpod',
                'android' => 'Android Kotlin + Jetpack Compose + MVVM',
                'maui' => '.NET MAUI + C# 12 + XAML + CommunityToolkit.Mvvm',
                'php_web' => 'PHP 8.2 + HTML5 + Tailwind CSS + Alpine.js',
            ],
            'design_tokens_count' => count($sampleTokens),
            'components_catalog' => $componentList,
            'wcag_compliance_level' => 'WCAG 2.1 AA',
            'responsive_breakpoints' => ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'],
        ];
    }
}
