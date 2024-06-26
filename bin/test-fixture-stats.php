<?php

// this is part of downgrade build

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Nette\Utils\Json;

require __DIR__ . '/../vendor/autoload.php';

$composerJsonFileContents = FileSystem::read(__DIR__ . '/../composer.json');

$composerJson = Json::decode($composerJsonFileContents, forceArrays: true);
$composerJson['replace']['phpstan/phpstan'] = $composerJson['require']['phpstan/phpstan'];

$modifiedComposerJsonFileContents = Json::encode($composerJson, pretty: true);
FileSystem::write(__DIR__ . '/../composer.json', $modifiedComposerJsonFileContents, null);

echo 'Done!' . PHP_EOL;
