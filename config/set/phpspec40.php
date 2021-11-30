<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $arrayType = new ArrayType(new MixedType(), new MixedType());

    $services->set(AddReturnTypeDeclarationRector::class)
        ->configure([new AddReturnTypeDeclaration('PhpSpec\ObjectBehavior', 'getMatchers', $arrayType)]);
};
