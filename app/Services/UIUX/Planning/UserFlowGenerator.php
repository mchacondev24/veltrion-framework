<?php

namespace Veltrion\Services\UIUX\Planning;

use Veltrion\Services\UIUX\Contracts\UserFlowGeneratorInterface;
use Veltrion\Services\UIUX\DTOs\FlowStep;
use Veltrion\Services\UIUX\DTOs\UserFlow;

class UserFlowGenerator implements UserFlowGeneratorInterface
{
    public function generateFlow(string $featureId, string $flowName, array $stepsData): UserFlow
    {
        $steps = [];
        $entryStepId = null;
        $exitStepIds = [];

        foreach ($stepsData as $s) {
            $step = new FlowStep(
                stepId: $s['id'],
                title: $s['title'],
                screenId: $s['screen_id'],
                action: $s['action'],
                targetScreenId: $s['target_screen_id'] ?? null,
                decisionCondition: $s['decision_condition'] ?? null,
                alternativeStepIds: $s['alternative_steps'] ?? [],
                errorStepIds: $s['error_steps'] ?? []
            );

            if ($entryStepId === null) {
                $entryStepId = $step->stepId;
            }

            if (empty($step->targetScreenId) && empty($step->alternativeStepIds)) {
                $exitStepIds[] = $step->stepId;
            }

            $steps[$step->stepId] = $step;
        }

        return new UserFlow(
            id: 'flow_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $flowName)),
            featureId: $featureId,
            name: $flowName,
            description: "Flow {$flowName} for feature {$featureId}",
            entryStepId: $entryStepId ?? 'start',
            steps: $steps,
            exitStepIds: $exitStepIds
        );
    }

    public function validateGraph(UserFlow $flow): array
    {
        $issues = [];
        $visited = [];
        $recursionStack = [];

        // Check entry step exists
        if (!isset($flow->steps[$flow->entryStepId])) {
            $issues[] = "Entry step '{$flow->entryStepId}' does not exist in flow.";
        }

        // Cycle detection in DAG
        $detectCycle = function (string $stepId) use (&$detectCycle, &$visited, &$recursionStack, $flow, &$issues) {
            $visited[$stepId] = true;
            $recursionStack[$stepId] = true;

            $step = $flow->steps[$stepId] ?? null;
            if ($step) {
                $neighbors = array_filter(array_merge(
                    [$step->targetScreenId],
                    $step->alternativeStepIds,
                    $step->errorStepIds
                ));

                foreach ($neighbors as $neighborId) {
                    if (isset($flow->steps[$neighborId])) {
                        if (!isset($visited[$neighborId])) {
                            $detectCycle($neighborId);
                        } elseif (!empty($recursionStack[$neighborId])) {
                            $issues[] = "Potential cycle detected involving step: {$neighborId}";
                        }
                    }
                }
            }

            $recursionStack[$stepId] = false;
        };

        if (isset($flow->steps[$flow->entryStepId])) {
            $detectCycle($flow->entryStepId);
        }

        return $issues;
    }

    public function exportMermaid(UserFlow $flow): string
    {
        $mermaid = "graph TD\n";
        foreach ($flow->steps as $step) {
            $label = addslashes($step->title);
            $mermaid .= "    {$step->stepId}[\"{$label}\"]\n";

            if ($step->targetScreenId && isset($flow->steps[$step->targetScreenId])) {
                $mermaid .= "    {$step->stepId} -->|{$step->action}| {$step->targetScreenId}\n";
            }

            foreach ($step->alternativeStepIds as $alt) {
                if (isset($flow->steps[$alt])) {
                    $mermaid .= "    {$step->stepId} -.->|alt| {$alt}\n";
                }
            }

            foreach ($step->errorStepIds as $err) {
                if (isset($flow->steps[$err])) {
                    $mermaid .= "    {$step->stepId} ==>|error| {$err}\n";
                }
            }
        }

        return $mermaid;
    }
}
