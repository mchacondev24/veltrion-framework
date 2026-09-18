<?php
namespace Veltrion\Services\Template\Blueprints;

use Veltrion\Services\Template\DTOs\TemplateManifest;
use Veltrion\Services\Template\DTOs\CapabilityMatrix;
use Veltrion\Services\Template\DTOs\FeaturePack;
use Veltrion\Services\Template\Enums\PlatformType;
use Veltrion\Services\Template\Enums\TemplateStatus;
use Veltrion\Services\Template\Enums\FeaturePackType;

class AndroidKotlinBlueprint
{
    public static function create(): TemplateManifest
    {
        $capabilities = new CapabilityMatrix(
            capabilities: [
                'native_android' => true,
                'jetpack_compose' => true,
                'coroutines_flow' => true,
                'room_database' => true,
                'clean_architecture' => true,
                'offline_first' => true,
            ],
            constraints: [
                'min_sdk' => '24',
                'target_sdk' => '34',
                'kotlin_version' => '1.9.20',
                'gradle_version' => '8.2.0',
            ]
        );

        $dbPack = new FeaturePack(
            id: 'android-room-db',
            name: 'Android Room Local Persistence Pack',
            type: FeaturePackType::DATABASE,
            description: 'Adds Android Jetpack Room Database, DAO interfaces, and Repository layer with Kotlin Flows',
            dependencies: [
                'androidx.room:room-runtime' => '2.6.1',
                'androidx.room:room-ktx' => '2.6.1'
            ],
            files: [
                'app/src/main/java/com/example/app/data/local/AppDatabase.kt' => "package com.example.app.data.local\n\nimport androidx.room.Database\nimport androidx.room.RoomDatabase\n\n@Database(entities = [], version = 1)\nabstract class AppDatabase : RoomDatabase() {}\n"
            ]
        );

        return new TemplateManifest(
            id: 'tpl-android-kotlin-v1',
            name: 'Android Native Jetpack Compose Architecture Blueprint',
            version: '2.0.0',
            platform: PlatformType::ANDROID,
            language: 'Kotlin',
            architecture: 'Clean Architecture + MVVM + Jetpack Compose + Coroutines/Flow',
            status: TemplateStatus::STABLE,
            capabilities: $capabilities,
            dependencies: [
                'androidx.core:core-ktx' => '1.12.0',
                'androidx.compose.ui:ui' => '1.6.0',
                'androidx.compose.material3:material3' => '1.2.0',
                'androidx.lifecycle:lifecycle-viewmodel-compose' => '2.7.0',
                'org.jetbrains.kotlinx:kotlinx-coroutines-android' => '1.7.3'
            ],
            structure: [
                'app/src/main/java/com/example/app/data' => 'Data sources, DTOs, Repository implementations',
                'app/src/main/java/com/example/app/domain' => 'Entities, Repository interfaces, UseCases',
                'app/src/main/java/com/example/app/ui' => 'Jetpack Compose screens, components, viewmodels, theme',
                'app/src/main/AndroidManifest.xml' => 'Android application manifest declaration',
                'app/build.gradle.kts' => 'Module-level Kotlin Gradle configuration',
                'build.gradle.kts' => 'Project-level Kotlin Gradle configuration'
            ],
            supportedFeaturePacks: [
                'database' => $dbPack,
            ],
            qualityGates: [
                'detekt_max_issues' => '0',
                'android_lint' => 'PASS',
            ],
            description: 'Native Android application template built with Jetpack Compose, Material 3, ViewModel, Kotlin Coroutines, and MVVM Clean Architecture.'
        );
    }
}
