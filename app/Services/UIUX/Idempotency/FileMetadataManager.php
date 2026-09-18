<?php

namespace Veltrion\Services\UIUX\Idempotency;

use Veltrion\Services\UIUX\DTOs\UIDiffResult;
use Veltrion\Services\UIUX\Enums\FileOwnershipType;

class FileMetadataManager
{
    private string $manifestPath;

    public function __construct(string $projectRoot)
    {
        $this->manifestPath = rtrim($projectRoot, '/') . '/.veltrion-ui-manifest.json';
    }

    public function loadManifest(): array
    {
        if (file_exists($this->manifestPath)) {
            $data = json_decode(file_get_contents($this->manifestPath), true);
            return is_array($data) ? $data : [];
        }
        return ['files' => []];
    }

    public function saveManifest(array $manifest): void
    {
        file_put_contents($this->manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param array<string, string> $newFiles RelPath => Content
     */
    public function computeDiff(string $projectRoot, array $newFiles): UIDiffResult
    {
        $manifest = $this->loadManifest();
        $records = $manifest['files'] ?? [];

        $created = [];
        $modified = [];
        $unchanged = [];
        $protectedSkipped = [];
        $conflicts = [];

        foreach ($newFiles as $relPath => $content) {
            $fullPath = rtrim($projectRoot, '/') . '/' . ltrim($relPath, '/');
            $newHash = hash('sha256', $content);

            if (!file_exists($fullPath)) {
                $created[] = $relPath;
                continue;
            }

            $currentDiskContent = file_get_contents($fullPath);
            $currentDiskHash = hash('sha256', $currentDiskContent);

            $meta = $records[$relPath] ?? null;
            $ownership = $meta['ownership'] ?? FileOwnershipType::GENERATED->value;

            if ($ownership === FileOwnershipType::PROTECTED->value || $ownership === FileOwnershipType::CUSTOM->value) {
                $protectedSkipped[] = $relPath;
                continue;
            }

            if ($currentDiskHash === $newHash) {
                $unchanged[] = $relPath;
            } else {
                // If disk content differs from recorded generated hash, user modified it
                $lastGenHash = $meta['hash'] ?? null;
                if ($lastGenHash && $currentDiskHash !== $lastGenHash) {
                    $conflicts[] = "Conflict in {$relPath}: file was modified manually since last generation.";
                } else {
                    $modified[] = $relPath;
                }
            }
        }

        return new UIDiffResult(
            createdFiles: $created,
            modifiedFiles: $modified,
            unchangedFiles: $unchanged,
            protectedSkippedFiles: $protectedSkipped,
            conflicts: $conflicts
        );
    }

    public function recordGeneratedFiles(array $filesWritten): void
    {
        $manifest = $this->loadManifest();
        foreach ($filesWritten as $relPath => $fullPath) {
            if (file_exists($fullPath)) {
                $manifest['files'][$relPath] = [
                    'hash' => hash('sha256', file_get_contents($fullPath)),
                    'generated_at' => date('Y-m-d H:i:s'),
                    'ownership' => FileOwnershipType::GENERATED->value,
                ];
            }
        }
        $this->saveManifest($manifest);
    }
}
