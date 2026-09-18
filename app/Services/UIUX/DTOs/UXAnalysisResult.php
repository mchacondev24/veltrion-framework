<?php

namespace Veltrion\Services\UIUX\DTOs;

class UXAnalysisResult
{
    /**
     * @param array<string, UserRole> $roles
     * @param array<string, UserPersona> $personas
     * @param array<string, UserJourney> $journeys
     * @param array<string, UserFlow> $flows
     */
    public function __construct(
        public readonly string $featureId,
        public readonly array $roles = [],
        public readonly array $personas = [],
        public readonly array $journeys = [],
        public readonly array $flows = [],
        public readonly array $recommendations = [],
        public readonly array $adrs = []
    ) {}

    public function toArray(): array
    {
        return [
            'feature_id' => $this->featureId,
            'roles' => array_map(fn($r) => $r instanceof UserRole ? $r->toArray() : $r, $this->roles),
            'personas' => array_map(fn($p) => $p instanceof UserPersona ? $p->toArray() : $p, $this->personas),
            'journeys' => array_map(fn($j) => $j instanceof UserJourney ? $j->toArray() : $j, $this->journeys),
            'flows' => array_map(fn($f) => $f instanceof UserFlow ? $f->toArray() : $f, $this->flows),
            'recommendations' => $this->recommendations,
            'adrs' => $this->adrs,
        ];
    }
}
