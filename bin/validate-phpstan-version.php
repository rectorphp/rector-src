<?php

declare(strict_types=1);

use Rector\Exception\ShouldNotHappenException;
use Rector\FileSystem\JsonFileSystem;
use Symfony\Component\Console\Command\Command;

require __DIR__ . '/../vendor/autoload.php';

$packageVersionResolver = new PackageVersionResolver();

$localPHPStanVersion = $packageVersionResolver->resolve(__DIR__ . '/../composer.json', 'phpstan/phpstan');
$downgradedPHPStanVersion = $packageVersionResolver->resolve(
    __DIR__ . '/../build/target-repository/composer.json',
    'phpstan/phpstan'
);

if ($localPHPStanVersion === $downgradedPHPStanVersion) {
    echo '[OK] PHPStan version in local and downgraded composer.json are equal, good job!' . PHP_EOL;
    exit(Command::SUCCESS);
}

echo sprintf(
    '[ERROR] PHPStan version in local composer.json is "%s", in downgraded "%s".%sMake sure they are equal first.',
    $localPHPStanVersion,
    $downgradedPHPStanVersion,
    PHP_EOL
) . PHP_EOL;
exit(Command::FAILURE);

final class PackageVersionResolver
{
    public function resolve(string $composerFilePath, string $packageName): string
    {
        $composerJson = JsonFileSystem::readFilePath($composerFilePath);
        $packageVersion = $composerJson['require'][$packageName] ?? null;

        if ($packageVersion === null) {
            throw new ShouldNotHappenException();
        }

        return $packageVersion;
    }
}
