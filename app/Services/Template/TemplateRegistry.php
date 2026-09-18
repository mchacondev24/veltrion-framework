<?php
namespace Veltrion\Services\Template;

use Veltrion\Services\Template\Contracts\TemplateRegistryInterface;
use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Blueprints\PhpApiCleanArchBlueprint;
use Veltrion\Services\Template\Blueprints\AngularWebAppBlueprint;
use Veltrion\Services\Template\Blueprints\ReactWebAppBlueprint;
use Veltrion\Services\Template\Blueprints\AndroidKotlinBlueprint;
use Veltrion\Services\Template\Blueprints\MauiDotnetBlueprint;
use Veltrion\Services\Template\Blueprints\FlutterDartBlueprint;

class TemplateRegistry implements TemplateRegistryInterface
{
    /** @var array<string, TemplateManifest> */
    private array $templates = [];

    public function __construct()
    {
        $this->loadDefaultBlueprints();
    }

    private function loadDefaultBlueprints(): void
    {
        $this->register(PhpApiCleanArchBlueprint::create());
        $this->register(AngularWebAppBlueprint::create());
        $this->register(ReactWebAppBlueprint::create());
        $this->register(AndroidKotlinBlueprint::create());
        $this->register(MauiDotnetBlueprint::create());
        $this->register(FlutterDartBlueprint::create());
    }

    public function register(TemplateManifest $manifest): void
    {
        $this->templates[$manifest->id] = $manifest;
    }

    public function get(string $templateId): ?TemplateManifest
    {
        return $this->templates[$templateId] ?? null;
    }

    public function all(): array
    {
        return $this->templates;
    }

    public function findByPlatform(PlatformType $platform): array
    {
        $result = [];
        foreach ($this->templates as $id => $manifest) {
            if ($manifest->platform === $platform) {
                $result[$id] = $manifest;
            }
        }
        return $result;
    }

    public function search(string $query): array
    {
        $q = strtolower(trim($query));
        $result = [];
        foreach ($this->templates as $id => $manifest) {
            if (str_contains(strtolower($manifest->id), $q) ||
                str_contains(strtolower($manifest->name), $q) ||
                str_contains(strtolower($manifest->language), $q) ||
                str_contains(strtolower($manifest->architecture), $q) ||
                str_contains(strtolower($manifest->description), $q)) {
                $result[$id] = $manifest;
            }
        }
        return $result;
    }
}
