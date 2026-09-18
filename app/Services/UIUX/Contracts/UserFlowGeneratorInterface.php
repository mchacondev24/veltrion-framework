<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\UserFlow;

interface UserFlowGeneratorInterface
{
    public function generateFlow(string $featureId, string $flowName, array $stepsData): UserFlow;
    public function validateGraph(UserFlow $flow): array; // returns array of errors / cycle warnings
    public function exportMermaid(UserFlow $flow): string;
}
