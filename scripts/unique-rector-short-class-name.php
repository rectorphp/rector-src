<?php

// this is part of downgrade build

declare(strict_types=1);

use Rector\Console\ExitCode;
use Rector\Scripts\Finder\RectorClassFinder;

require __DIR__ . '/../vendor/autoload.php';

$rectorClassFinder = new RectorClassFinder();
$rectorClassNames = $rectorClassFinder->find([
    __DIR__ . '/../rules',
    __DIR__ . '/../vendor/rector/rector-doctrine',
    __DIR__ . '/../vendor/rector/rector-phpunit',
    __DIR__ . '/../vendor/rector/rector-symfony',
    __DIR__ . '/../vendor/rector/rector-downgrade-php',
]);

/**
 * @param string[] $classNames
 * @return string[]
 */
function getShortClassNames(array $classNames): array
{
    $shortClassNames = [];
    foreach ($classNames as $className) {
        $shortClassNames[] = substr($className, strrpos($className, '\\') + 1);
    }

    return $shortClassNames;
}

/**
 * @param string[] $shortClassNames
 * @return string[]
 */
function filterDuplicatedValues(array $shortClassNames): array
{
    $classNamesToCounts = array_count_values($shortClassNames);
    $duplicatedShortClassNames = [];

    foreach ($classNamesToCounts as $className => $count) {
        if ($count === 1) {
            // unique, skip
            continue;
        }

        $duplicatedShortClassNames[] = $className;
    }

    return $duplicatedShortClassNames;
}

$shortClassNames = getShortClassNames($rectorClassNames);
$duplicatedShortClassNames = filterDuplicatedValues($shortClassNames);

if ($duplicatedShortClassNames === []) {
    echo "All Rector class names are unique!\n";
    exit(ExitCode::SUCCESS);
}

echo "The following Rector class names are duplicated:\n";
foreach ($duplicatedShortClassNames as $duplicatedShortClassName) {
    echo sprintf("- %s\n", $duplicatedShortClassName);
}

exit(ExitCode::FAILURE);
