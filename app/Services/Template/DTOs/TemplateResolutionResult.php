<?php
namespace Veltrion\Services\Template\DTOs;

class TemplateResolutionResult
{
    /**
     * @param TemplateManifest|null $template Best matching template
     * @param float $score Match score (0.0 - 100.0)
     * @param array<string, mixed> $matchDetails Explanation of score breakdown
     * @param array<string, FeaturePack> $selectedFeaturePacks Resolved feature packs to apply
     */
    public function __construct(
        public ?TemplateManifest $template,
        public float $score,
        public array $matchDetails = [],
        public array $selectedFeaturePacks = []
    ) {}

    public function isSuccess(): bool
    {
        return $this->template !== null && $this->score > 0;
    }

    public function toArray(): array
    {
        $packs = [];
        foreach ($this->selectedFeaturePacks as $key => $pack) {
            $packs[$key] = $pack->toArray();
        }

        return [
            'success' => $this->isSuccess(),
            'score' => $this->score,
            'template' => $this->template ? $this->template->toArray() : null,
            'match_details' => $this->matchDetails,
            'selected_feature_packs' => $packs,
        ];
    }
}
