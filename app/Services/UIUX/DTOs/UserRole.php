<?php

namespace Veltrion\Services\UIUX\DTOs;

class UserRole
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly array $permissions = [],
        public readonly array $accessibleScreens = [],
        public readonly array $allowedActions = [],
        public readonly array $restrictedActions = [],
        public readonly array $primaryGoals = []
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'permissions' => $this->permissions,
            'accessible_screens' => $this->accessibleScreens,
            'allowed_actions' => $this->allowedActions,
            'restricted_actions' => $this->restrictedActions,
            'primary_goals' => $this->primaryGoals,
        ];
    }
}
