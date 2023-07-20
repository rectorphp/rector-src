<?php

declare(strict_types=1);

use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(ReplaceArgumentDefaultValueRector::class, [
            new ReplaceArgumentDefaultValue(
                \Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture\ReplaceInConstructor::class,
                \Rector\Core\ValueObject\MethodName::CONSTRUCT,
                0,
                'some_value',
                'SomeClass::SOME_CONSTANT'
            ),

            new ReplaceArgumentDefaultValue(
                'Symfony\Component\DependencyInjection\Definition',
                'setScope',
                0,
                'Symfony\Component\DependencyInjection\ContainerBuilder::SCOPE_PROTOTYPE',
                false
            ),
            new ReplaceArgumentDefaultValue('Symfony\Component\Yaml\Yaml', 'parse', 1, [
                false,
                false,
                true,
            ], 'Symfony\Component\Yaml\Yaml::PARSE_OBJECT_FOR_MAP'),

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
