<?php
namespace Veltrion\Services\Knowledge\Providers;

use Veltrion\Services\Template\TemplateEngine;

class TemplateKnowledgeProvider
{
    private TemplateEngine $templateEngine;

    public function __construct(?TemplateEngine $engine = null)
    {
        $this->templateEngine = $engine ?? new TemplateEngine();
    }

    public function getTemplateKnowledge(): array
    {
        $templates = $this->templateEngine->getRegistry()->all();
        $knowledge = [];

        foreach ($templates as $id => $manifest) {
            $knowledge[] = [
                'id' => $manifest->id,
                'type' => 'project_template',
                'title' => $manifest->name,
                'platform' => $manifest->platform->value,
                'architecture' => $manifest->architecture,
                'version' => $manifest->version,
                'capabilities' => array_keys(array_filter($manifest->capabilities->capabilities)),
                'feature_packs' => array_keys($manifest->supportedFeaturePacks),
                'quality_gates' => $manifest->qualityGates,
            ];
        }

        return [
            'provider' => 'TemplateKnowledgeProvider',
            'version' => '1.0.0',
            'templates' => $knowledge,
        ];
    }
}
