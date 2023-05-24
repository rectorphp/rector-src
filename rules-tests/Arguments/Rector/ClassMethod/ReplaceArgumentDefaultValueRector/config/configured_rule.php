<?php

declare(strict_types=1);
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(ReplaceArgumentDefaultValueRector::class, [

            new ReplaceArgumentDefaultValue(
                'Symfony\Component\DependencyInjection\Definition',
                'setScope',
                0,
                ContainerBuilder::class . '::SCOPE_PROTOTYPE',
                false
            ),
            new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'parse', 1, [
                false,
                false,
                true,
            ], Yaml::class . '::PARSE_OBJECT_FOR_MAP'),
            new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'parse', 1, [
                false,
                true,
            ], Yaml::class . '::PARSE_OBJECT'),
            new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'parse', 1, false, 0),
            new ReplaceArgumentDefaultValue(
                'Symfony\Component\Yaml\Yaml',
                'parse',
                1,
                true,
                Yaml::class . '::PARSE_EXCEPTION_ON_INVALID_TYPE'
            ),
            new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'dump', 3, [
                false,
                true,
            ], Yaml::class . '::DUMP_OBJECT'),
            new ReplaceArgumentDefaultValue(
                'Symfony\Component\Yaml\Yaml',
                'dump',
                3,
                true,
                Yaml::class . '::DUMP_EXCEPTION_ON_INVALID_TYPE'
            ),

            new ReplaceArgumentDefaultValue(
                'Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeClassWithAnyDefaultValue',
                'someMethod',
                0,
                ReplaceArgumentDefaultValue::ANY_VALUE_BEFORE,
                []
            ),
            new ReplaceArgumentDefaultValue(
                'Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source\SomeClassWithAnyDefaultValue',
                'paramWithNull',
                0,
                null,
                []
            ),
        ]);
};
