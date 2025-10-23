<?php

// as those classes can be used in IDE autocomplete and break node instanceof

declare(strict_types=1);

use Nette\Utils\Strings;
use Rector\Scripts\Finder\FixtureFinder;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;

require __DIR__ . '/../vendor/autoload.php';

$fixtureFiles = FixtureFinder::find([__DIR__ . '/../tests', __DIR__ . '/../rules-tests']);

// get short node names

$nodeFileFinder = \Symfony\Component\Finder\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/../vendor/nikic/php-parser/lib/PhpParser/Node');

/** @var \Symfony\Component\Finder\SplFileInfo[] $nodeFileInfos */
$nodeFileInfos = iterator_to_array($nodeFileFinder->getIterator());

$shortNodeClassNames = [];
foreach ($nodeFileInfos as $nodeFileInfo) {
    $shortNodeClassNames[] = $nodeFileInfo->getBasename('.php');
}

$symfonyStyle = new SymfonyStyle(new ArrayInput([]), new ConsoleOutput());

$hasErrors = false;

foreach ($fixtureFiles as $fixtureFileInfo) {
    $shortClassNameMatch = Strings::match(
        $fixtureFileInfo->getContents(),
        '/\b(?:class|interface)\s+(?<name>[A-Z][A-Za-z0-9_]*)/'
    );
    if ($shortClassNameMatch === null) {
        continue;
    }

    $fixtureClassName = $shortClassNameMatch['name'];
    if (! in_array($fixtureClassName, $shortNodeClassNames, true)) {
        continue;
    }

    $symfonyStyle->writeln(sprintf(
        'Fixture class name "%s" conflicts with native short node name.%sChange it to different to avoid IDE incorrect autocomplete:%s%s%s',
        $fixtureClassName,
        PHP_EOL,
        PHP_EOL,
        $fixtureFileInfo->getRealPath(),
        PHP_EOL
    ));

    $hasErrors = true;
}

if ($hasErrors) {
    exit(1);
}

exit(0);
