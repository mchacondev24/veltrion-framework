<?php

namespace Veltrion\Services\UIUX\DTOs;

class UserJourney
{
    /**
     * @param array<int, string> $stepDescriptions
     * @param array<int, string> $flowIds
     */
    public function __construct(
        public readonly string $id,
        public readonly string $personaId,
        public readonly string $goal,
        public readonly string $entryPoint,
        public readonly array $stepDescriptions = [],
        public readonly array $flowIds = [],
        public readonly string $outcome = ''
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'persona_id' => $this->personaId,
            'goal' => $this->goal,
            'entry_point' => $this->entryPoint,
            'steps' => $this->stepDescriptions,
            'flow_ids' => $this->flowIds,
            'outcome' => $this->outcome,
        ];
    }
}
