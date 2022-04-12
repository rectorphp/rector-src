<?php

declare(strict_types=1);

use Rector\Set\ValueObject\SetList;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(SetList::DEAD_CODE);
};
