<?php
namespace Veltrion\Services\Template;

use Veltrion\Services\Template\Contracts\TemplateRegistryInterface;
use Veltrion\Services\Template\Contracts\TemplateResolverInterface;
use Veltrion\Services\Template\Contracts\TemplateGeneratorInterface;
use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\TemplateResolutionResult;
use Veltrion\Services\Template\DTOs\GenerationResult;
use Veltrion\Services\Template\Enums\PlatformType;

class TemplateEngine
{
    private TemplateRegistryInterface $registry;
    private TemplateResolverInterface $resolver;
    private TemplateGeneratorInterface $generator;

    public function __construct(
        ?TemplateRegistryInterface $registry = null,
        ?TemplateResolverInterface $resolver = null,
        ?TemplateGeneratorInterface $generator = null
    ) {
        $this->registry = $registry ?? new TemplateRegistry();
        $this->resolver = $resolver ?? new TemplateResolver($this->registry);
        $this->generator = $generator ?? new TemplateGenerator();
    }

    public function getRegistry(): TemplateRegistryInterface
    {
        return $this->registry;
    }

    public function getResolver(): TemplateResolverInterface
    {
        return $this->resolver;
    }

    public function getGenerator(): TemplateGeneratorInterface
    {
        return $this->generator;
    }

    /**
     * Resolve and build blueprint project structure in a single command
     */
    public function createProjectFromTemplate(
        PlatformType|string $platform,
        string $outputPath,
        array $variables = [],
        array $requiredCapabilities = [],
        array $constraints = [],
        array $featurePacks = []
    ): array {
        $resolution = $this->resolver->resolve(
            platform: $platform,
            requiredCapabilities: $requiredCapabilities,
            constraints: $constraints,
            requestedFeaturePacks: $featurePacks
        );

        if (!$resolution->isSuccess() || $resolution->template === null) {
            return [
                'success' => false,
                'error' => 'Could not resolve matching template blueprint for platform: ' . (is_string($platform) ? $platform : $platform->value),
                'resolution' => $resolution->toArray(),
            ];
        }

        $genResult = $this->generator->generate(
            manifest: $resolution->template,
            outputPath: $outputPath,
            variables: $variables,
            featurePackIds: array_keys($resolution->selectedFeaturePacks)
        );

        return [
            'success' => true,
            'template_id' => $resolution->template->id,
            'template_name' => $resolution->template->name,
            'platform' => $resolution->template->platform->value,
            'resolution_score' => $resolution->score,
            'generation' => $genResult->toArray(),
        ];
    }
}
