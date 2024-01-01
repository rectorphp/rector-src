<?php

declare(strict_types=1);

use Rector\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector;
use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Fixture\ReplaceInConstructor;
use Rector\ValueObject\MethodName;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(ReplaceArgumentDefaultValueRector::class, [
            // special case for constructor
            new ReplaceArgumentDefaultValue(
                ReplaceInConstructor::class,
                MethodName::CONSTRUCT,
                0,
                'some value',
                'SomeClass::SOME_CONSTANT'
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
