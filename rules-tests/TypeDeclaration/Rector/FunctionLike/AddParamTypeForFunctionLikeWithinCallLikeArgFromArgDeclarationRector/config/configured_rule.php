<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector::class, [
            new AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration(
                'SomeNamespace\SomeClass',
                'someCall',
                1,
                0,
                0
            ),
            new AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration(
                'SomeNamespace\SomeClass',
                'someOtherCall',
                1,
                0,
                0
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
