<?php

declare(strict_types=1);

use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeParamRector;
use Rector\Renaming\ValueObject\RenameFunctionLikeParamWithinCallLikeParam;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameFunctionLikeParamWithinCallLikeParamRector::class, [
            new RenameFunctionLikeParamWithinCallLikeParam(
                'SomeNamespace\SomeClass',
                'someCall',
                0,
                0,
                'query'
            ),
            new RenameFunctionLikeParamWithinCallLikeParam(
                'SomeNamespace\SomeClassForNamed',
                'someCall',
                'callback',
                0,
                'query',
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
