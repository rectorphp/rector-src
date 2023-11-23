<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Doctrine\Set\DoctrineSetList;

return static function (RectorConfig $rectorConfig): void {
        $rectorConfig->sets([
            SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
            DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
};
