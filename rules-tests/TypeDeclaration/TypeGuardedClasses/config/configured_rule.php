<?php

declare(strict_types=1);

use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\Config\RectorConfig;
use Rector\Tests\TypeDeclaration\TypeGuardedClasses\Source\GuardedRepository;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->typeGuardedClasses([
        GuardedRepository::class,
        'Rector\Tests\TypeDeclaration\TypeGuardedClasses\Fixture\SkipGuardedTrait',
    ]);

    $rectorConfig->rule(StrictArrayParamDimFetchRector::class);

    $rectorConfig->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
        new AddReturnTypeDeclaration(
            GuardedRepository::class,
            'getData',
            new ArrayType(new MixedType(), new MixedType())
        ),
    ]);
};
