<?php
namespace Veltrion\Services\Template;

use Veltrion\Services\Template\Contracts\TemplateResolverInterface;
use Veltrion\Services\Template\Contracts\TemplateRegistryInterface;
use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\TemplateResolutionResult;
use Veltrion\Services\Template\DTOs\FeaturePack;
use Veltrion\Services\Template\Enums\PlatformType;

class TemplateResolver implements TemplateResolverInterface
{
    public function __construct(
        private readonly TemplateRegistryInterface $registry
    ) {}

    public function resolve(
        PlatformType|string $platform,
        array $requiredCapabilities = [],
        array $constraints = [],
        array $requestedFeaturePacks = []
    ): TemplateResolutionResult {
        $platformEnum = $platform instanceof PlatformType
            ? $platform
            : $this->parsePlatformString($platform);

        $candidates = $platformEnum
            ? $this->registry->findByPlatform($platformEnum)
            : $this->registry->all();

        if (empty($candidates)) {
            return new TemplateResolutionResult(
                template: null,
                score: 0.0,
                matchDetails: ['error' => 'No candidate templates found for platform: ' . ($platformEnum?->value ?? (string)$platform)]
            );
        }

        $bestTemplate = null;
        $bestScore = -1.0;
        $bestDetails = [];

        foreach ($candidates as $manifest) {
            $eval = $this->evaluateTemplate($manifest, $requiredCapabilities, $constraints);
            if ($eval['score'] > $bestScore) {
                $bestScore = $eval['score'];
                $bestTemplate = $manifest;
                $bestDetails = $eval['details'];
            }
        }

        $selectedPacks = [];
        if ($bestTemplate !== null) {
            foreach ($requestedFeaturePacks as $packKey) {
                if (isset($bestTemplate->supportedFeaturePacks[$packKey])) {
                    $pack = $bestTemplate->supportedFeaturePacks[$packKey];
                    if ($pack instanceof FeaturePack) {
                        $selectedPacks[$packKey] = $pack;
                    }
                }
            }
        }

        return new TemplateResolutionResult(
            template: $bestTemplate,
            score: max(0.0, $bestScore),
            matchDetails: $bestDetails,
            selectedFeaturePacks: $selectedPacks
        );
    }

    private function parsePlatformString(string $platformStr): ?PlatformType
    {
        $normalized = strtolower(trim($platformStr));
        foreach (PlatformType::cases() as $type) {
            if ($type->value === $normalized || str_contains(strtolower($type->label()), $normalized)) {
                return $type;
            }
        }
        return null;
    }

    private function evaluateTemplate(
        TemplateManifest $manifest,
        array $requiredCapabilities,
        array $constraints
    ): array {
        $score = 50.0; // Base platform match score
        $details = ['platform_match' => true];

        // Capabilities score
        if (!empty($requiredCapabilities)) {
            $supportedCount = 0;
            foreach ($requiredCapabilities as $cap) {
                if ($manifest->capabilities->supports($cap)) {
                    $supportedCount++;
                }
            }
            $capRatio = $supportedCount / count($requiredCapabilities);
            $score += ($capRatio * 30.0);
            $details['capability_match_ratio'] = $capRatio;
        } else {
            $score += 30.0;
            $details['capability_match_ratio'] = 1.0;
        }

        // Constraints score
        $constraintMatch = true;
        foreach ($constraints as $key => $value) {
            if ($manifest->capabilities->hasConstraint($key)) {
                $actual = $manifest->capabilities->getConstraint($key);
                if ($actual !== null && strtolower($actual) !== strtolower((string)$value)) {
                    // Soft penalty
                    $score -= 10.0;
                    $constraintMatch = false;
                }
            }
        }
        $details['constraint_match'] = $constraintMatch;

        if ($manifest->status->value === 'stable') {
            $score += 20.0;
        }

        return [
            'score' => round(min(100.0, $score), 2),
            'details' => $details,
        ];
    }
}
