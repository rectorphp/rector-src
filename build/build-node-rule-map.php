<?php

declare(strict_types=1);

use Nette\Loaders\RobotLoader;
use PhpParser\Node;
use Rector\Console\Style\SymfonyStyleFactory;
use Rector\Contract\Rector\RectorInterface;
use Rector\Scripts\Finder\RectorClassFinder;
use Rector\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Command\Command;

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

// 1. collect rule classes from core + symfony/doctrine/phpunit
$ruleDirectories = [
    $rootDirectory . '/rules',
    $rootDirectory . '/vendor/rector/rector-symfony/rules',
    $rootDirectory . '/vendor/rector/rector-symfony/src',
    $rootDirectory . '/vendor/rector/rector-doctrine/rules',
    $rootDirectory . '/vendor/rector/rector-doctrine/src',
    $rootDirectory . '/vendor/rector/rector-phpunit/rules',
    $rootDirectory . '/vendor/rector/rector-phpunit/src',
];

$ruleDirectories = array_filter($ruleDirectories, 'is_dir');

$rectorClassFinder = new RectorClassFinder();
$rectorClasses = $rectorClassFinder->find($ruleDirectories);

// keep only active Rector rules, skip PostRectors and other *Rector.php helpers
$nodeTypesByRectorClass = [];
foreach ($rectorClasses as $rectorClass) {
    if (! is_a($rectorClass, RectorInterface::class, true)) {
        continue;
    }

    $reflectionClass = new ReflectionClass($rectorClass);
    $rector = $reflectionClass->newInstanceWithoutConstructor();

    $nodeTypesByRectorClass[$rectorClass] = $rector->getNodeTypes();
}

// 2. collect all concrete PhpParser node classes, core + Rector custom nodes (e.g. FileNode)
$robotLoader = new RobotLoader();
$robotLoader->addDirectory($rootDirectory . '/vendor/nikic/php-parser/lib/PhpParser/Node');
$robotLoader->addDirectory($rootDirectory . '/src/PhpParser/Node');
$robotLoader->setCacheDirectory(sys_get_temp_dir() . '/rector-node-rule-map');
$robotLoader->refresh();

$nodeClasses = [];
foreach (array_keys($robotLoader->getIndexedClasses()) as $class) {
    $reflectionClass = new ReflectionClass($class);
    if (! $reflectionClass->isInstantiable()) {
        continue;
    }

    if (! $reflectionClass->isSubclassOf(Node::class)) {
        continue;
    }

    $nodeClasses[] = $class;
}

// 3. resolve rules per concrete node class, mirroring RectorNodeTraverser::getVisitorsForNode() is_a() match
$nodeRuleMap = [];
foreach ($nodeClasses as $nodeClass) {
    $matchedRectorClasses = [];
    foreach ($nodeTypesByRectorClass as $rectorClass => $nodeTypes) {
        foreach ($nodeTypes as $nodeType) {
            if (is_a($nodeClass, $nodeType, true)) {
                $matchedRectorClasses[] = $rectorClass;
                break;
            }
        }
    }

    sort($matchedRectorClasses);
    $nodeRuleMap[$nodeClass] = $matchedRectorClasses;
}

ksort($nodeRuleMap);

$outputFilePath = $rootDirectory . '/config/node-rule-map.json';
$json = json_encode($nodeRuleMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
file_put_contents($outputFilePath, $json);

$symfonyStyle->success(sprintf(
    'Generated map for %d node classes from %d rules into %s',
    count($nodeRuleMap),
    count($nodeTypesByRectorClass),
    'config/node-rule-map.json'
));

return Command::SUCCESS;
