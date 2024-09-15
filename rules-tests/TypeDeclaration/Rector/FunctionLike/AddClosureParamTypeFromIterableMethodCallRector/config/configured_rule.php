<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\Collection;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector\Source\NonIteratorClass;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector;
use Rector\TypeDeclaration\ValueObject\AddClosureParamTypeFromObject;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddClosureParamTypeFromIterableMethodCallRector::class, [
            new AddClosureParamTypeFromObject(Collection::class, 'map', 0, 0),
            new AddClosureParamTypeFromObject(NonIteratorClass::class, 'map', 0, 0),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
