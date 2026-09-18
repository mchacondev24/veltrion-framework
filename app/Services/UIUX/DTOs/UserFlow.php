<?php

namespace Veltrion\Services\UIUX\DTOs;

class UserFlow
{
    /**
     * @param array<string, FlowStep> $steps
     */
    public function __construct(
        public readonly string $id,
        public readonly string $featureId,
        public readonly string $name,
        public readonly string $description,
        public readonly string $entryStepId,
        public readonly array $steps = [],
        public readonly array $exitStepIds = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'feature_id' => $this->featureId,
            'name' => $this->name,
            'description' => $this->description,
            'entry_step_id' => $this->entryStepId,
            'steps' => array_map(fn($s) => $s instanceof FlowStep ? $s->toArray() : $s, $this->steps),
            'exit_step_ids' => $this->exitStepIds,
        ];
    }
}
