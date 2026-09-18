<?php

namespace Veltrion\Services\CICD\Providers;

use Veltrion\Services\CICD\Providers\Contracts\CIProviderInterface;

class GitHubActionsProvider implements CIProviderInterface
{
    public function getName(): string
    {
        return 'GitHub Actions';
    }

    public function getTargetFilePath(): string
    {
        return '.github/workflows/ci.yml';
    }

    public function generateConfig(): string
    {
        return "name: Veltrion Framework CI/CD Pipeline\n\n" .
            "on:\n" .
            "  push:\n" .
            "    branches: [ master, main, develop ]\n" .
            "  pull_request:\n" .
            "    branches: [ master, main ]\n\n" .
            "jobs:\n" .
            "  build-and-test:\n" .
            "    runs-on: ubuntu-latest\n" .
            "    steps:\n" .
            "      - name: Checkout Code\n" .
            "        uses: actions/checkout@v4\n\n" .
            "      - name: Setup PHP Environment\n" .
            "        uses: shivammathur/setup-php@v2\n" .
            "        with:\n" .
            "          php-version: '8.2'\n" .
            "          extensions: mbstring, pdo, pdo_sqlite, json, zip\n\n" .
            "      - name: Install Dependencies\n" .
            "        run: composer install --prefer-dist --no-progress\n\n" .
            "      - name: PHP Lint Validation\n" .
            "        run: php -l bootstrap/app.php\n\n" .
            "      - name: Run Test Suite\n" .
            "        run: php cli test\n\n" .
            "      - name: Run CI Pipeline & Quality Gates\n" .
            "        run: php cli ci:run --profile standard\n\n" .
            "      - name: Verify Release Readiness\n" .
            "        run: php cli release:check\n";
    }
}
