<?php

declare(strict_types=1);

use Rector\Composer\ComposerJsonPackageVersionResolver;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\FunctionLike\NarrowWideUnionReturnTypeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->singleton(
        ComposerJsonPackageVersionResolver::class,
        static fn (): ComposerJsonPackageVersionResolver => new ComposerJsonPackageVersionResolver(
            __DIR__ . '/../Source/composer.json'
        )
    );
    $rectorConfig->rule(NarrowWideUnionReturnTypeRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::UNION_TYPES);
};
