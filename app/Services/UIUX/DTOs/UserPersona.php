<?php

namespace Veltrion\Services\UIUX\DTOs;

class UserPersona
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $roleId,
        public readonly string $archetype,
        public readonly string $bio,
        public readonly array $painPoints = [],
        public readonly array $motivations = [],
        public readonly array $devices = ['mobile', 'desktop']
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role_id' => $this->roleId,
            'archetype' => $this->archetype,
            'bio' => $this->bio,
            'pain_points' => $this->painPoints,
            'motivations' => $this->motivations,
            'devices' => $this->devices,
        ];
    }
}
