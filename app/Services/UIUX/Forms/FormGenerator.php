<?php

namespace Veltrion\Services\UIUX\Forms;

use Veltrion\Services\UIUX\DTOs\FormField;
use Veltrion\Services\UIUX\DTOs\ValidationRule;

class FormGenerator
{
    /**
     * Translates a dictionary of fields/types into formal FormField DTOs
     * @param array<string, array|string> $fieldDefinitions
     * @return array<string, FormField>
     */
    public function generateFields(array $fieldDefinitions): array
    {
        $fields = [];

        foreach ($fieldDefinitions as $name => $def) {
            $type = is_array($def) ? ($def['type'] ?? 'text') : $def;
            $label = is_array($def) ? ($def['label'] ?? ucwords(str_replace('_', ' ', $name))) : ucwords(str_replace('_', ' ', $name));
            $required = is_array($def) ? ($def['required'] ?? false) : false;
            $options = is_array($def) ? ($def['options'] ?? []) : [];
            $helpText = is_array($def) ? ($def['help_text'] ?? null) : null;
            $gridColumns = is_array($def) ? ($def['grid_columns'] ?? 12) : 12;

            $rules = [];
            if ($required) {
                $rules[] = new ValidationRule('required', true, "{$label} is required");
            }

            if ($type === 'email' || str_contains($name, 'email')) {
                $type = 'email';
                $rules[] = new ValidationRule('email', true, "Enter a valid email address");
            } elseif ($type === 'number' || $type === 'integer' || $type === 'int') {
                $type = 'number';
                $rules[] = new ValidationRule('numeric', true, "{$label} must be numeric");
            } elseif ($type === 'date') {
                $type = 'date';
                $rules[] = new ValidationRule('date', true, "{$label} must be a valid date");
            }

            $fields[$name] = new FormField(
                name: $name,
                label: $label,
                type: $type,
                placeholder: "Enter {$label}",
                defaultValue: null,
                rules: $rules,
                options: $options,
                isRequired: $required,
                helpText: $helpText,
                gridColumns: $gridColumns
            );
        }

        return $fields;
    }
}
