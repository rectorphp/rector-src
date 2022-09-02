<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Source\AnotherTrait;
use Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Source\SomeInterface;
use Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Source\SomeTrait;
use Rector\Tests\Transform\Rector\Class_\AddInterfaceByTraitRector\Source\TopMostInterface;
use Rector\Transform\Rector\Class_\AddInterfaceByTraitRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(AddInterfaceByTraitRector::class, [
            SomeTrait::class => SomeInterface::class,
            AnotherTrait::class => TopMostInterface::class,
        ]);
};
