<?php

declare(strict_types=1);

use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\DowngradeSetList;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(DowngradeLevelSetList::DOWN_TO_PHP_53);
    $containerConfigurator->import(DowngradeSetList::PHP_53);
};
