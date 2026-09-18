<?php

namespace Veltrion\Services\UIUX\DTOs;

class ScreenAction
{
    public function __construct(
        public readonly string $id,
        public readonly string $label,
        public readonly string $type, // navigation, submit, modal, api_call, delete, export
        public readonly ?string $target = null,
        public readonly array $requiredPermissions = [],
        public readonly bool $isPrimary = false,
        public readonly bool $requiresConfirmation = false,
        public readonly ?string $confirmationMessage = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'type' => $this->type,
            'target' => $this->target,
            'required_permissions' => $this->requiredPermissions,
            'is_primary' => $this->isPrimary,
            'requires_confirmation' => $this->requiresConfirmation,
            'confirmation_message' => $this->confirmationMessage,
        ];
    }
}
