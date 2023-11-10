<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;

return static function (RectorConfig $rectorConfig): void {
        $rectorConfig->sets([
            SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,
            DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
};
