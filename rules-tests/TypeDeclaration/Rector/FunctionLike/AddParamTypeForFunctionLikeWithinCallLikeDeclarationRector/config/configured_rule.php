<?php

declare(strict_types=1);

use PHPStan\Type\StringType;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector::class, [
            new AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration(
                'SomeNamespace\SomeClass',
                'someCall',
                0,
                0,
                new StringType()
            ),
            new AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration(
                'SomeNamespace\SomeClassForNamed',
                'someCall',
                'callback',
                0,
                new StringType()
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
