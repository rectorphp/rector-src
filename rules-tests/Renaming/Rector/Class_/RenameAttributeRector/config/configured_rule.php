<?php

declare(strict_types=1);
use Rector\Renaming\ValueObject\RenameAttribute;

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Class_\RenameAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameAttributeRector::class, [
            new RenameAttribute(
                'Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\SimpleRoute',
                'Rector\Tests\Renaming\Rector\Class_\RenameAttributeRector\Source\NextRoute'
            ),
        ]);
};
