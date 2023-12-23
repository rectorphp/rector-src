<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use Rector\Arguments\NodeAnalyzer\ArgumentAddingScope;
use Rector\Arguments\Rector\ClassMethod\ArgumentAdderRector;
use Rector\Arguments\ValueObject\ArgumentAdder;
use Rector\Arguments\ValueObject\ArgumentAdderWithoutDefaultValue;
use Rector\Config\RectorConfig;
use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Fixture\WithoutDefaultValue;
use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeClass;
use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeContainerBuilder;
use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeMultiArg;
use Rector\Tests\Arguments\Rector\ClassMethod\ArgumentAdderRector\Source\SomeParentClient;

return static function (RectorConfig $rectorConfig): void {
    $arrayType = new ArrayType(new MixedType(), new MixedType());

    $rectorConfig
        ->ruleWithConfiguration(ArgumentAdderRector::class, [
            // covers https://github.com/rectorphp/rector/issues/4267
            new ArgumentAdder(
                SomeContainerBuilder::class,
                'sendResetLinkResponse',
                0,
                'request',
                null,
                new ObjectType('Illuminate\Http\Illuminate\Http')
            ),
            new ArgumentAdder(SomeContainerBuilder::class, 'compile', 0, 'isCompiled', false),
            new ArgumentAdder(SomeContainerBuilder::class, 'addCompilerPass', 2, 'priority', 0, new IntegerType()),
            // scoped
            new ArgumentAdder(
                SomeParentClient::class,
                'submit',
                2,
                'serverParameters',
                [],
                $arrayType,
                ArgumentAddingScope::SCOPE_PARENT_CALL
            ),
            new ArgumentAdder(
                SomeParentClient::class,
                'submit',
                2,
                'serverParameters',
                [],
                $arrayType,
                ArgumentAddingScope::SCOPE_CLASS_METHOD
            ),
            new ArgumentAdder(SomeClass::class, 'withoutTypeOrDefaultValue', 0, 'arguments', [], $arrayType),
            new ArgumentAdder(SomeMultiArg::class, 'run', 2, 'c', 4),
            new ArgumentAdder(SomeClass::class, 'someMethod', 0, 'default', 1),
            new ArgumentAdderWithoutDefaultValue(WithoutDefaultValue::class, 'someMethod', 0, 'foo', $arrayType),
        ]);
};
