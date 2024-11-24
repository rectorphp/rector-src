<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DynamicDocBlockPropertyToNativePropertyRector::class);
    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            'Rector\Tests\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector\Source\SomeDependency'
                => 'stdClass'
        ]
    );
};
