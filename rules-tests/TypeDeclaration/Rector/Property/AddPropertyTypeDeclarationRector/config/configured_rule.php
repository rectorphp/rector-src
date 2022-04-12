<?php

declare(strict_types=1);

use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use Rector\Tests\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector\Source\ParentClassWithProperty;
use Rector\Tests\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector\Source\SomeTraitWithProperty;
use Rector\TypeDeclaration\Rector\Property\AddPropertyTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddPropertyTypeDeclaration;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AddPropertyTypeDeclarationRector::class)
        ->configure([
            new AddPropertyTypeDeclaration(ParentClassWithProperty::class, 'name', new StringType()),
            new AddPropertyTypeDeclaration(SomeTraitWithProperty::class, 'value', new IntegerType()),
        ]);
};
