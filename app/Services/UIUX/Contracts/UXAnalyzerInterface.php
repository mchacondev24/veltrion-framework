<?php

namespace Veltrion\Services\UIUX\Contracts;

use Veltrion\Services\UIUX\DTOs\UXAnalysisResult;

interface UXAnalyzerInterface
{
    public function analyzeFeature(string $featureId, array $requirements = []): UXAnalysisResult;
    public function extractRolesAndPersonas(array $requirements): array;
}
