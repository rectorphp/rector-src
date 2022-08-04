<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\FunctionLike\UnionTypesRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([UnionTypesRector::class, AddArrayReturnDocTypeRector::class]);
    $rectorConfig->importNames();
};
