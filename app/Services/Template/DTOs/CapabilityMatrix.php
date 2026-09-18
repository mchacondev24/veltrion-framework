<?php
namespace Veltrion\Services\Template\DTOs;

class CapabilityMatrix
{
    /**
     * @param array<string, bool> $capabilities Map of capability names to support status (e.g. ['auth' => true, 'offline' => false])
     * @param array<string, string> $constraints Constraints or minimum requirements (e.g. ['min_sdk' => '21', 'php_version' => '^8.2'])
     */
    public function __construct(
        public array $capabilities = [],
        public array $constraints = []
    ) {}

    public function supports(string $capability): bool
    {
        return $this->capabilities[$capability] ?? false;
    }

    public function hasConstraint(string $key): bool
    {
        return isset($this->constraints[$key]);
    }

    public function getConstraint(string $key, ?string $default = null): ?string
    {
        return $this->constraints[$key] ?? $default;
    }

    public function toArray(): array
    {
        return [
            'capabilities' => $this->capabilities,
            'constraints' => $this->constraints,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            capabilities: $data['capabilities'] ?? [],
            constraints: $data['constraints'] ?? []
        );
    }
}
