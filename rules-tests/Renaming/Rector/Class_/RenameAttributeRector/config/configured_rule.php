<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Class_\RenameAttributeRector;
use Rector\Renaming\ValueObject\RenameAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameAttributeRector::class, [
            new RenameAttribute(
                'Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleRoute',
                'Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\NextRoute',
            ),
            new RenameAttribute(
                'Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleParamAttribute',
                'Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\NextParamAttribute',
            ),
        ]);
};
