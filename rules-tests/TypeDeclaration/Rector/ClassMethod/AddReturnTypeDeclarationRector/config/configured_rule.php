<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\StringType;
use PHPStan\Type\UnionType;
use PHPStan\Type\VoidType;
use Rector\Config\RectorConfig;
use Rector\StaticTypeMapper\ValueObject\Type\SimpleStaticType;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Fixture\ReturnOfStatic;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Fixture\ReturnTheMixed;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Source\DataTransformerInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Source\FileInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Source\FolderInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Source\FormTypeInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Source\PHPUnitTestCase;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $nullableStringType = new UnionType([new NullType(), new StringType()]);
    $nullableObjectType = new UnionType([new NullType(), new ObjectType(FileInterface::class)]);

    $rectorConfig
        ->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
            new AddReturnTypeDeclaration(PHPUnitTestCase::class, 'tearDown', new VoidType()),
            new AddReturnTypeDeclaration(ReturnTheMixed::class, 'create', new MixedType(true)),
            new AddReturnTypeDeclaration(
                ReturnOfStatic::class,
                'create',
                new SimpleStaticType(ReturnOfStatic::class)
            ),
            new AddReturnTypeDeclaration(DataTransformerInterface::class, 'transform', new MixedType()),
            new AddReturnTypeDeclaration(FormTypeInterface::class, 'getParent', $nullableStringType),
            new AddReturnTypeDeclaration(
                FolderInterface::class,
                'create',
                $nullableObjectType
            ),
        ]);
};
