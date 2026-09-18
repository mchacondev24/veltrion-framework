<?php
namespace Veltrion\Services\Template\Contracts;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\GenerationResult;

interface TemplateGeneratorInterface
{
    /**
     * @param TemplateManifest $manifest Target template manifest
     * @param string $outputPath Path where code blueprint should be generated
     * @param array<string, mixed> $variables Dynamic variable replacements (e.g. ['appName' => 'ShopApp', 'namespace' => 'App\\Shop'])
     * @param array<string> $featurePackIds Feature pack IDs to overlay
     */
    public function generate(
        TemplateManifest $manifest,
        string $outputPath,
        array $variables = [],
        array $featurePackIds = []
    ): GenerationResult;
}
