<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector;
use Rector\TypeDeclaration\ValueObject\AddClosureParamTypeFromArg;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddClosureParamTypeFromArgClassStringRector::class, [
            new AddClosureParamTypeFromArg(
                \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Source\SimpleContainer::class,
                'someCall',
                1,
                0,
                0
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
