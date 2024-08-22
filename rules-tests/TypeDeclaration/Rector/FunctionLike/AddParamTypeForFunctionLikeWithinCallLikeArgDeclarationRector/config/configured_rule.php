<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector::class, [
            new AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration(
                'SomeNamespace\SomeClass',
                'someCall',
                0,
                0,
                new StringType()
            ),
            new AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration(
                'SomeNamespace\SomeClassForNamed',
                'someCall',
                'callback',
                0,
                new StringType()
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
