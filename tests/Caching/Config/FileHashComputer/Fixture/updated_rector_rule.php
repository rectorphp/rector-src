<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Expression\RemoveDeadStmtRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        DeclareStrictTypesRector::class,
        RemoveDeadStmtRector::class,
    ]);
};
