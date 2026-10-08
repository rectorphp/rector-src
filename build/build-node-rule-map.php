<?php

declare(strict_types=1);

use Rector\Console\Style\SymfonyStyleFactory;
use Rector\Scripts\Finder\RectorClassFinder;
use Rector\Scripts\NodeRuleMap\NodeRuleMapFactory;
use Rector\Util\Reflection\PrivatesAccessor;

$possiblePaths = [
    // rector-src
    __DIR__ . '/../vendor/autoload.php',
    // rector package dependency
    __DIR__ . '/../../../../vendor/autoload.php',
];

foreach ($possiblePaths as $possiblePath) {
    if (! file_exists($possiblePath)) {
        continue;
    }

    require $possiblePath;
    break;
}

$rootDirectory = __DIR__ . '/..';

$symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
$symfonyStyle = $symfonyStyleFactory->create();

// core rules + symfony/doctrine/phpunit extension packages
$ruleDirectories = [
    $rootDirectory . '/rules',
    $rootDirectory . '/vendor/rector/rector-symfony/rules',
    $rootDirectory . '/vendor/rector/rector-symfony/src',
    $rootDirectory . '/vendor/rector/rector-doctrine/rules',
    $rootDirectory . '/vendor/rector/rector-doctrine/src',
    $rootDirectory . '/vendor/rector/rector-phpunit/rules',
    $rootDirectory . '/vendor/rector/rector-phpunit/src',
];

$nodeDirectories = [
    $rootDirectory . '/vendor/nikic/php-parser/lib/PhpParser/Node',
    $rootDirectory . '/src/PhpParser/Node',
];

$nodeRuleMapFactory = new NodeRuleMapFactory(new RectorClassFinder());
$nodeRuleMap = $nodeRuleMapFactory->create($ruleDirectories, $nodeDirectories);

$json = json_encode($nodeRuleMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
file_put_contents($rootDirectory . '/config/node-rule-map.json', $json);

$symfonyStyle->success(sprintf(
    'Generated map for %d node classes into config/node-rule-map.json',
    count($nodeRuleMap)
));
