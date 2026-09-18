<?php

namespace Veltrion\Services\CICD\Providers;

use Veltrion\Services\CICD\Providers\Contracts\CIProviderInterface;

class GitLabCIProvider implements CIProviderInterface
{
    public function getName(): string
    {
        return 'GitLab CI';
    }

    public function getTargetFilePath(): string
    {
        return '.gitlab-ci.yml';
    }

    public function generateConfig(): string
    {
        return "image: php:8.2-cli\n\n" .
            "stages:\n" .
            "  - validate\n" .
            "  - test\n" .
            "  - quality_gates\n" .
            "  - release\n\n" .
            "cache:\n" .
            "  paths:\n" .
            "    - vendor/\n\n" .
            "before_script:\n" .
            "  - apt-get update -y && apt-get install -y git unzip libzip-dev\n" .
            "  - docker-php-ext-install zip pdo pdo_mysql\n" .
            "  - curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer\n" .
            "  - composer install --prefer-dist --no-progress\n\n" .
            "lint:\n" .
            "  stage: validate\n" .
            "  script:\n" .
            "    - php -l bootstrap/app.php\n\n" .
            "unit_tests:\n" .
            "  stage: test\n" .
            "  script:\n" .
            "    - php cli test\n\n" .
            "quality_gates:\n" .
            "  stage: quality_gates\n" .
            "  script:\n" .
            "    - php cli ci:run --profile standard\n\n" .
            "release_check:\n" .
            "  stage: release\n" .
            "  script:\n" .
            "    - php cli release:check\n";
    }
}
