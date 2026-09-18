<?php

namespace Veltrion\Services\UIUX\DTOs;

use Veltrion\Services\UIUX\Enums\UIStateType;

class ScreenState
{
    public function __construct(
        public readonly UIStateType $type,
        public readonly string $description,
        public readonly ?string $message = null,
        public readonly ?string $actionLabel = null,
        public readonly ?string $actionTarget = null
    ) {}

    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'description' => $this->description,
            'message' => $this->message,
            'action_label' => $this->actionLabel,
            'action_target' => $this->actionTarget,
        ];
    }
}
