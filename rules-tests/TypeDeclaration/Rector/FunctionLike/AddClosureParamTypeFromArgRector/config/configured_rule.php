<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Source\SimpleContainer;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector;
use Rector\TypeDeclaration\ValueObject\AddClosureParamTypeFromArg;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddClosureParamTypeFromArgRector::class, [
            new AddClosureParamTypeFromArg(SimpleContainer::class, 'someCall', 1, 0, 0),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
