<?php
namespace Veltrion\Services\Template\Contracts;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\Enums\PlatformType;

interface TemplateRegistryInterface
{
    public function register(TemplateManifest $manifest): void;
    public function get(string $templateId): ?TemplateManifest;
    /** @return array<string, TemplateManifest> */
    public function all(): array;
    /** @return array<string, TemplateManifest> */
    public function findByPlatform(PlatformType $platform): array;
    /** @return array<string, TemplateManifest> */
    public function search(string $query): array;
}
