<?php

// as those classes can be used in IDE autocomplete and break node instanceof

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$fixtureFiles = \Rector\Scripts\Finder\FixtureFinder::find([__DIR__ . '/../tests', __DIR__ . '/../rules-tests']);

foreach ($fixtureFiles as $fixtureFileInfo) {
    $shortClassNameMatch = \Nette\Utils\Strings::match($fixtureFileInfo->getContents(), '/\b(?:class|interface)\s+([A-Z][A-Za-z0-9_]*)/');
    dump($shortClassNameMatch);
}
