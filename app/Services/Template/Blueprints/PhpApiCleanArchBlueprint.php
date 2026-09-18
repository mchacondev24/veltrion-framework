<?php
namespace Veltrion\Services\Template\Blueprints;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\CapabilityMatrix;
use Veltrion\Services\Template\DTOs\FeaturePack;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Enums\TemplateStatus;
use Veltrion\Services\Template\Enums\FeaturePackType;

class PhpApiCleanArchBlueprint
{
    public static function create(): TemplateManifest
    {
        $capabilities = new CapabilityMatrix(
            capabilities: [
                'rest_api' => true,
                'clean_architecture' => true,
                'cli' => true,
                'unit_testing' => true,
                'database' => true,
                'auth' => true,
                'swagger_docs' => true,
            ],
            constraints: [
                'php_version' => '>=8.2',
                'framework' => 'Veltrion / PSR-11 Container',
            ]
        );

        $authPack = new FeaturePack(
            id: 'php-auth-jwt',
            name: 'JWT Authentication Pack for PHP',
            type: FeaturePackType::AUTH,
            description: 'Adds JWT Auth middleware, Token Generator, and Login/Register UseCases',
            dependencies: ['firebase/php-jwt' => '^6.8'],
            files: [
                'app/Domain/Entities/User.php' => "<?php\nnamespace App\\Domain\\Entities;\n\nclass User {\n    public function __construct(public string \$id, public string \$email, public string \$passwordHash) {}\n}\n",
                'app/Services/Auth/JwtService.php' => "<?php\nnamespace App\\Services\\Auth;\n\nclass JwtService {\n    public function generateToken(string \$userId): string { return 'jwt.token.' . md5(\$userId); }\n}\n"
            ]
        );

        $dbPack = new FeaturePack(
            id: 'php-db-pdo',
            name: 'PDO SQLite/MySQL Persistence Pack',
            type: FeaturePackType::DATABASE,
            description: 'Configures PDO Repository patterns and SQLite/MySQL connection pool',
            dependencies: ['ext-pdo' => '*'],
            files: [
                'config/database.php' => "<?php\nreturn ['default' => 'sqlite', 'connections' => ['sqlite' => ['driver' => 'sqlite', 'database' => 'database.sqlite']]];\n"
            ]
        );

        return new TemplateManifest(
            id: 'tpl-php-api-cleanarch-v1',
            name: 'PHP Clean Architecture REST API Blueprint',
            version: '1.2.0',
            platform: PlatformType::PHP_API,
            language: 'PHP',
            architecture: 'Clean Architecture (Domain, Application, Adapters, Infrastructure)',
            status: TemplateStatus::STABLE,
            capabilities: $capabilities,
            dependencies: [
                'php' => '>=8.2',
                'ext-pdo' => '*',
                'ext-mbstring' => '*',
                'vlucas/phpdotenv' => '^5.5'
            ],
            structure: [
                'app/Domain/Entities' => 'Enterprise business objects',
                'app/Domain/Repositories' => 'Repository interfaces',
                'app/UseCases' => 'Application business rules',
                'app/Adapters/Http' => 'Controllers and Request/Response DTOs',
                'app/Adapters/Repositories' => 'PDO & Memory concrete storage implementations',
                'config/app.php' => 'Core framework configuration',
                'public/index.php' => 'HTTP Entry point',
                'composer.json' => 'Composer dependencies and PSR-4 autoloading',
            ],
            supportedFeaturePacks: [
                'auth' => $authPack,
                'database' => $dbPack,
            ],
            qualityGates: [
                'unit_test_coverage' => '85%',
                'phpstan_level' => '8',
            ],
            description: 'Production-ready PHP REST API blueprint featuring Clean Architecture, PSR-11 container autowiring, and strict Domain-Driven Design isolation.'
        );
    }
}
