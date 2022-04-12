<?php

declare(strict_types=1);

use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Symfony\Set\TwigSetList;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_60);
    $containerConfigurator->import(TwigSetList::TWIG_20);
};
