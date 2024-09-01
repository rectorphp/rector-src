<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector;
use Rector\TypeDeclaration\ValueObject\AddClosureParamTypeFromObject;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddClosureParamTypeFromObjectRector::class, [
            new AddClosureParamTypeFromObject(SimpleContainer::class, 'someCall', 1, 0),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
