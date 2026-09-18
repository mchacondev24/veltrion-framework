<?php

namespace Veltrion\Services\UIUX\DTOs;

class FlowStep
{
    public function __construct(
        public readonly string $stepId,
        public readonly string $title,
        public readonly string $screenId,
        public readonly string $action,
        public readonly ?string $targetScreenId = null,
        public readonly ?string $decisionCondition = null,
        public readonly array $alternativeStepIds = [],
        public readonly array $errorStepIds = []
    ) {}

    public function toArray(): array
    {
        return [
            'step_id' => $this->stepId,
            'title' => $this->title,
            'screen_id' => $this->screenId,
            'action' => $this->action,
            'target_screen_id' => $this->targetScreenId,
            'decision_condition' => $this->decisionCondition,
            'alternative_step_ids' => $this->alternativeStepIds,
            'error_step_ids' => $this->errorStepIds,
        ];
    }
}
