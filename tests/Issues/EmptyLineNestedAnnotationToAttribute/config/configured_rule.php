<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
};
