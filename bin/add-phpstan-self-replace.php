<?php

// this is part of downgrade build

declare(strict_types=1);

use PackageVersions\Versions;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;

require __DIR__ . '/../vendor/autoload.php';

$composerJsonFileContents = FileSystem::read(__DIR__ . '/../composer.json');

$composerJson = Json::decode($composerJsonFileContents, forceArrays: true);
// result output is like: // 1.0.0@0beec7b5ea3f0fdbc95d0dd47f3c5bc275da8a33
[$phpstanVersion, ] = explode('@', Versions::getVersion('phpstan/phpstan'));
$composerJson['replace']['phpstan/phpstan'] = $phpstanVersion;

$modifiedComposerJsonFileContents = Json::encode($composerJson, pretty: true);
FileSystem::write(__DIR__ . '/../composer.json', $modifiedComposerJsonFileContents, null);

echo 'Done!' . PHP_EOL;
