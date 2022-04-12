<?php

declare(strict_types=1);

use Rector\Set\ValueObject\DowngradeSetList;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(DowngradeSetList::PHP_81);
};
