<?php

declare(strict_types=1);

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
                0,
                true,
            ),
            new AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclaration(
                'SomeNamespace\SomeClass',
                'someOtherCall',
                1,
                0,
                0,
                false,
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
