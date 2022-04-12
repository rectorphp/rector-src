<?php

declare(strict_types=1);

use Rector\PHPUnit\Set\PHPUnitSetList;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_60);
};
