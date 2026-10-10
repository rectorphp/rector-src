<?php

declare(strict_types=1);

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Console\Style\SymfonyStyleFactory;
use Rector\Scripts\Finder\RectorClassFinder;
use Rector\Scripts\NodeRuleMap\NodeRuleMapFactory;
use Rector\Util\Reflection\PrivatesAccessor;
use Symfony\Component\Console\Command\Command;

require __DIR__ . '/../vendor/autoload.php';

$rootDirectory = __DIR__ . '/..';

$symfonyStyleFactory = new SymfonyStyleFactory(new PrivatesAccessor());
$symfonyStyle = $symfonyStyleFactory->create();

// extension packages move independently (dev-main), so only the in-repo core rules are validated;
// extension rules ship best-effort and the traverser falls back to is_a() resolution for any drift
$siblingNamespacePrefixes = ['Rector\\Symfony\\', 'Rector\\Doctrine\\', 'Rector\\PHPUnit\\'];

$nodeDirectories = [
    $rootDirectory . '/vendor/nikic/php-parser/lib/PhpParser/Node',
    $rootDirectory . '/src/PhpParser/Node',
];

$nodeRuleMapFactory = new NodeRuleMapFactory(new RectorClassFinder());
$expectedCoreMap = $nodeRuleMapFactory->create([$rootDirectory . '/rules'], $nodeDirectories);

$committedMap = Json::decode(FileSystem::read($rootDirectory . '/config/node-rule-map.json'), true);
if (! is_array($committedMap)) {
    $symfonyStyle->error('The committed config/node-rule-map.json is not valid.');
    exit(Command::FAILURE);
}

// keep only core rules from the committed map
$committedCoreMap = [];
foreach ($committedMap as $nodeClass => $ruleClasses) {
    $coreRuleClasses = array_filter($ruleClasses, static fn (string $ruleClass): bool => array_all($siblingNamespacePrefixes, fn (string $siblingNamespacePrefix): bool => ! str_starts_with($ruleClass, $siblingNamespacePrefix)));

    $committedCoreMap[$nodeClass] = array_values($coreRuleClasses);
}

if (Json::encode($committedCoreMap) === Json::encode($expectedCoreMap)) {
    $symfonyStyle->success('The core rules in config/node-rule-map.json are up to date.');
    exit(Command::SUCCESS);
}

$symfonyStyle->error(
    'The core rules in config/node-rule-map.json are out of date. Run "composer build-node-rule-map" and commit the result.'
);

// report the first differing node classes to make the fix obvious
foreach ($expectedCoreMap as $nodeClass => $expectedRuleClasses) {
    $committedRuleClasses = $committedCoreMap[$nodeClass] ?? [];
    if ($committedRuleClasses === $expectedRuleClasses) {
        continue;
    }

    $symfonyStyle->writeln('<comment>' . $nodeClass . '</comment>');
    $symfonyStyle->writeln('  missing: ' . implode(', ', array_values(array_diff($expectedRuleClasses, $committedRuleClasses))));
    $symfonyStyle->writeln('  unexpected: ' . implode(', ', array_values(array_diff($committedRuleClasses, $expectedRuleClasses))));
}

exit(Command::FAILURE);
