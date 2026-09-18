<?php

namespace Veltrion\Services\UIUX\Planning;

use Veltrion\Services\UIUX\Contracts\UXAnalyzerInterface;
use Veltrion\Services\UIUX\DTOs\FlowStep;
use Veltrion\Services\UIUX\DTOs\UserFlow;
use Veltrion\Services\UIUX\DTOs\UserJourney;
use Veltrion\Services\UIUX\DTOs\UserPersona;
use Veltrion\Services\UIUX\DTOs\UserRole;
use Veltrion\Services\UIUX\DTOs\UXAnalysisResult;

class UXAnalyzer implements UXAnalyzerInterface
{
    public function analyzeFeature(string $featureId, array $requirements = []): UXAnalysisResult
    {
        $rolesAndPersonas = $this->extractRolesAndPersonas($requirements);
        $roles = $rolesAndPersonas['roles'];
        $personas = $rolesAndPersonas['personas'];

        // Default flows derived from requirements or standard CRUD
        $flows = [];
        $featureSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $featureId));

        $step1 = new FlowStep('step_list', 'View records', 'screen_list', 'browse', 'step_create');
        $step2 = new FlowStep('step_create', 'Fill creation form', 'screen_form', 'submit', 'step_success');
        $step3 = new FlowStep('step_success', 'Confirmation and redirect', 'screen_list', 'view');

        $mainFlow = new UserFlow(
            id: "flow_{$featureSlug}_standard",
            featureId: $featureId,
            name: "Primary Operation Flow for {$featureId}",
            description: "End-to-end user navigation flow for creating and managing records",
            entryStepId: 'step_list',
            steps: [
                'step_list' => $step1,
                'step_create' => $step2,
                'step_success' => $step3,
            ],
            exitStepIds: ['step_success']
        );
        $flows[$mainFlow->id] = $mainFlow;

        // Journeys
        $journeys = [];
        foreach ($personas as $persona) {
            $journeys[] = new UserJourney(
                id: "journey_{$persona->id}",
                personaId: $persona->id,
                goal: "Successfully manage operations in {$featureId}",
                entryPoint: "/dashboard",
                stepDescriptions: [
                    "User logs in and reaches dashboard",
                    "Navigates to {$featureId} management",
                    "Filters data or submits new transaction",
                    "Receives immediate visual feedback"
                ],
                flowIds: [$mainFlow->id],
                outcome: "Operation accomplished with zero friction"
            );
        }

        $recommendations = [
            "Use optimistic UI updates during data mutation to increase perceived speed.",
            "Display skeleton loaders during initial data fetching.",
            "Ensure destructive actions require explicit modal confirmation."
        ];

        $adrs = [
            [
                'title' => "ADR-UIUX-001: Mobile-first responsive hierarchy",
                'status' => "ACCEPTED",
                'context' => "Ensure uniform user experience across smartphones, tablets and desktop screens.",
                'decision' => "Use 12-column fluid grid collapsing to single column on XS/SM breakpoints."
            ]
        ];

        return new UXAnalysisResult(
            featureId: $featureId,
            roles: $roles,
            personas: $personas,
            journeys: $journeys,
            flows: $flows,
            recommendations: $recommendations,
            adrs: $adrs
        );
    }

    public function extractRolesAndPersonas(array $requirements): array
    {
        // Standard Enterprise Personas
        $adminRole = new UserRole(
            id: 'role_admin',
            name: 'Administrator',
            description: 'Full system privileges and configuration',
            permissions: ['*'],
            accessibleScreens: ['*'],
            allowedActions: ['create', 'read', 'update', 'delete', 'export', 'audit'],
            primaryGoals: ['System health', 'Data security', 'User management']
        );

        $operatorRole = new UserRole(
            id: 'role_operator',
            name: 'Operator / Manager',
            description: 'Daily operational tasks and reporting',
            permissions: ['read', 'create', 'update', 'export'],
            accessibleScreens: ['dashboard', 'crud_list', 'form', 'detail'],
            allowedActions: ['create', 'read', 'update', 'export'],
            primaryGoals: ['Efficiency', 'Accuracy', 'Daily throughput']
        );

        $adminPersona = new UserPersona(
            id: 'persona_admin_alex',
            name: 'Alex Rivera (IT Admin)',
            roleId: 'role_admin',
            archetype: 'System Administrator',
            bio: 'Manages enterprise setup, security policies and user access.',
            painPoints: ['Complex onboarding', 'Lack of audit visibility'],
            motivations: ['Reliability', 'Automation', 'Fast configuration']
        );

        $operatorPersona = new UserPersona(
            id: 'persona_op_maria',
            name: 'Maria Santos (Operations Lead)',
            roleId: 'role_operator',
            archetype: 'Power User',
            bio: 'Processes daily transactions and monitors team performance.',
            painPoints: ['Slow UI transitions', 'Tedious repetitive data entry'],
            motivations: ['Keyboard shortcuts', 'Fast bulk actions', 'Clean readable tables']
        );

        return [
            'roles' => [
                'role_admin' => $adminRole,
                'role_operator' => $operatorRole,
            ],
            'personas' => [
                'persona_admin_alex' => $adminPersona,
                'persona_op_maria' => $operatorPersona,
            ],
        ];
    }
}
