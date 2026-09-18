<?php
namespace Veltrion\Services\Commercial\Packaging;

/* VELTRION_PROTECTION_GUARD v1.0 | Commercial Build Protection */
if(!defined('VELTRION_RUNTIME_GUARD') && file_exists(__DIR__ . '/../bootstrap/guard.php')){ @include_once __DIR__ . '/../bootstrap/guard.php'; }
  class PackageResult { public function __construct( public bool $success, public string $packageName, public string $packagePath, public string $checksumSha256, public int $sizeBytes, public PackageManifest $manifest, public array $logs = [] ) {} public function toArray(): array { return [ 'success' => $this->success, 'package_name' => $this->packageName, 'package_path' => $this->packagePath, 'checksum_sha256' => $this->checksumSha256, 'size_bytes' => $this->sizeBytes, 'manifest' => $this->manifest->toArray(), 'logs' => $this->logs ]; } } 