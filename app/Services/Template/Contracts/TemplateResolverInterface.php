<?php
namespace Veltrion\Services\Template\Contracts;

use Veltrion\Services\Template\DTOs\TemplateResolutionResult;
use Veltrion\Services\Template\Enums\PlatformType;

interface TemplateResolverInterface
{
    /**
     * @param PlatformType|string $platform Target platform or string alias
     * @param array<string> $requiredCapabilities Required feature capabilities (e.g. ['auth', 'offline'])
     * @param array<string, string> $constraints Technical constraints (e.g. ['language' => 'typescript'])
     * @param array<string> $requestedFeaturePacks Feature pack IDs requested
     */
    public function resolve(
        PlatformType|string $platform,
        array $requiredCapabilities = [],
        array $constraints = [],
        array $requestedFeaturePacks = []
    ): TemplateResolutionResult;
}
