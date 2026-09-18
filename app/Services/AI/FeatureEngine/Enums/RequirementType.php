<?php
namespace Veltrion\Services\AI\FeatureEngine\Enums;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  class RequirementType { public const FUNCTIONAL = 'FUNCTIONAL'; public const NON_FUNCTIONAL = 'NON_FUNCTIONAL'; public const BUSINESS = 'BUSINESS'; public const SECURITY = 'SECURITY'; public const PERFORMANCE = 'PERFORMANCE'; public const UX = 'UX'; public const TECHNICAL = 'TECHNICAL'; public static function getAll(): array { return [ self::FUNCTIONAL, self::NON_FUNCTIONAL, self::BUSINESS, self::SECURITY, self::PERFORMANCE, self::UX, self::TECHNICAL ]; } } 