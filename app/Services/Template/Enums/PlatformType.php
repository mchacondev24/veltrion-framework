<?php
namespace Veltrion\Services\Template\Enums;

enum PlatformType: string
{
    CASE PHP_API = 'php-api';
    CASE ANGULAR = 'angular';
    CASE REACT = 'react';
    CASE ANDROID = 'android';
    CASE MAUI = 'maui';
    CASE FLUTTER = 'flutter';

    public function label(): string
    {
        return match($this) {
            self::PHP_API => 'PHP API / Clean Architecture',
            self::ANGULAR => 'Angular Web Application',
            self::REACT => 'React Web Application',
            self::ANDROID => 'Android Native (Kotlin)',
            self::MAUI => 'MAUI Cross-Platform (.NET/C#)',
            self::FLUTTER => 'Flutter Multi-Platform (Dart)',
        };
    }
}
