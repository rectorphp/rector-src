<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Set\ValueObject\DowngradeSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([DowngradeLevelSetList::DOWN_TO_PHP_74, DowngradeSetList::PHP_74]);
};
