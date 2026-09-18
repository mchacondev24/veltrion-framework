<?php

namespace Veltrion\Services\UIUX\DTOs;

class DataSource
{
    public function __construct(
        public readonly string $id,
        public readonly string $type, // api_rest, usecase, local_state, graphql
        public readonly string $endpointOrUseCase,
        public readonly string $method = 'GET',
        public readonly array $parameters = [],
        public readonly ?string $entityClass = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'endpoint_or_use_case' => $this->endpointOrUseCase,
            'method' => $this->method,
            'parameters' => $this->parameters,
            'entity_class' => $this->entityClass,
        ];
    }
}
