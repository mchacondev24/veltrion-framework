<?php

namespace Veltrion\Services\UIUX\Validation;

use Veltrion\Services\UIUX\Contracts\UIValidatorInterface;
use Veltrion\Services\UIUX\DTOs\NavigationTree;
use Veltrion\Services\UIUX\DTOs\ScreenModel;
use Veltrion\Services\UIUX\Enums\UIStateType;

class UIValidator implements UIValidatorInterface
{
    public function validateArchitecture(array $screens, NavigationTree $navigation, array $flows = []): array
    {
        $issues = [];

        // 1. Check for orphaned / unreachable screens
        $navScreenIds = [];
        $collectNavScreens = function ($nodes) use (&$collectNavScreens, &$navScreenIds) {
            foreach ($nodes as $node) {
                if ($node->screenId) {
                    $navScreenIds[] = $node->screenId;
                }
                if (!empty($node->children)) {
                    $collectNavScreens($node->children);
                }
            }
        };
        $collectNavScreens($navigation->nodes);

        foreach ($screens as $id => $screen) {
            // Screen must have at least one way to be accessed (either directly via navigation or an action from another screen)
            $isLinkedByAction = false;
            foreach ($screens as $otherScreen) {
                if ($otherScreen->id === $id) continue;
                foreach ($otherScreen->actions as $action) {
                    if ($action->target === $screen->route || $action->target === $screen->id) {
                        $isLinkedByAction = true;
                        break 2;
                    }
                }
            }

            if (!in_array($id, $navScreenIds) && !$isLinkedByAction && $screen->layoutType !== 'modal' && $id !== 'dashboard') {
                $issues[] = [
                    'type' => 'ORPHAN_SCREEN',
                    'severity' => 'WARNING',
                    'screen_id' => $id,
                    'message' => "Screen '{$screen->title}' ({$id}) is not referenced in primary navigation or action links."
                ];
            }

            // 2. Check for missing UI states on data-driven screens
            if (!empty($screen->dataSources)) {
                if (!isset($screen->states[UIStateType::LOADING->value])) {
                    $issues[] = [
                        'type' => 'MISSING_STATE',
                        'severity' => 'MEDIUM',
                        'screen_id' => $id,
                        'message' => "Screen '{$id}' connects to data sources but lacks an explicit LOADING state."
                    ];
                }

                if ($screen->layoutType === 'crud_list' && !isset($screen->states[UIStateType::EMPTY->value])) {
                    $issues[] = [
                        'type' => 'MISSING_STATE',
                        'severity' => 'MEDIUM',
                        'screen_id' => $id,
                        'message' => "List screen '{$id}' lacks an EMPTY state representation for zero-result conditions."
                    ];
                }
            }

            // 3. Check for dead ends (screens without any outbound action or back navigation)
            if (empty($screen->actions) && $screen->layoutType !== 'modal') {
                $issues[] = [
                    'type' => 'DEAD_END',
                    'severity' => 'HIGH',
                    'screen_id' => $id,
                    'message' => "Screen '{$id}' has zero interactive actions, creating a potential navigation dead end."
                ];
            }
        }

        return $issues;
    }
}
