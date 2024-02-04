<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FunctionLike\RenameFunctionLikeParamWithinCallLikeArgRector;
use Rector\Renaming\ValueObject\RenameFunctionLikeParamWithinCallLikeArg;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameFunctionLikeParamWithinCallLikeArgRector::class, [
            new RenameFunctionLikeParamWithinCallLikeArg('SomeNamespace\SomeClass', 'someCall', 0, 0, 'query'),
            new RenameFunctionLikeParamWithinCallLikeArg(
                'SomeNamespace\SomeClassForNamed',
                'someCall',
                'callback',
                0,
                'query',
            ),
        ]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
