<?php

namespace Veltrion\Services\UIUX\Adapters;

use Veltrion\Services\UIUX\Contracts\PlatformUIAdapterInterface;
use InvalidArgumentException;

class PlatformUIAdapterRegistry
{
    /**
     * @var array<string, PlatformUIAdapterInterface>
     */
    private array $adapters = [];

    public function __construct()
    {
        $this->register(new ReactUIAdapter());
        $this->register(new AngularUIAdapter());
        $this->register(new FlutterUIAdapter());
        $this->register(new AndroidComposeUIAdapter());
        $this->register(new MauiUIAdapter());
        $this->register(new PhpWebUIAdapter());
    }

    public function register(PlatformUIAdapterInterface $adapter): void
    {
        $this->adapters[$adapter->getPlatformName()] = $adapter;
    }

    public function get(string $platform): PlatformUIAdapterInterface
    {
        $key = strtolower(trim($platform));
        // Common alias mappings
        $key = match($key) {
            'react_web', 'reactjs' => 'react',
            'angular_web' => 'angular',
            'android_kotlin', 'compose' => 'android',
            'dotnet_maui', 'csharp_maui' => 'maui',
            'flutter_dart' => 'flutter',
            'php', 'php_api' => 'php_web',
            default => $key,
        };

        if (!isset($this->adapters[$key])) {
            throw new InvalidArgumentException("Unsupported UI platform adapter: '{$platform}'. Supported platforms: " . implode(', ', array_keys($this->adapters)));
        }

        return $this->adapters[$key];
    }

    public function has(string $platform): bool
    {
        try {
            $this->get($platform);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * @return array<string, PlatformUIAdapterInterface>
     */
    public function all(): array
    {
        return $this->adapters;
    }
}
