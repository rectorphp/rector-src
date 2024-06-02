<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\IncreaseDeclareStrictTypesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([DeclareStrictTypesRector::class, IncreaseDeclareStrictTypesRector::class]);
};
