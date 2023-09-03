<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([

        // only spaced/comment added, no need to clear cache
        DeclareStrictTypesRector::class

    ]);
};
