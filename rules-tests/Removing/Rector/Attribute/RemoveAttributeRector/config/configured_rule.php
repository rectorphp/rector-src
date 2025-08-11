<?php

declare(strict_types=1);

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use Rector\Config\RectorConfig;
use Rector\Removing\Rector\Attribute\RemoveAttributeRector;
use Rector\Removing\ValueObject\RemoveAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassAttribute',
            [Class_::class]
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromTraitAttribute',
            [Trait_::class]
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromInterfaceAttribute',
            [Interface_::class]
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromEnumAttribute',
            [Enum_::class]
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromEnumCaseAttribute',
            [EnumCase::class]
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromPropertyAttribute',
            [Property::class]
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassConstantAttribute',
            [ClassConst::class],
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromClassMethodAttribute',
            [ClassMethod::class],
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromFunctionAttribute',
            [Function_::class],
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveFromParameterAttribute',
            [Param::class],
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveAttributeRector::class, [
        new RemoveAttribute(
            'Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\Source\Attribute\RemoveEverywhereAttribute',
        ),
    ]);
};
