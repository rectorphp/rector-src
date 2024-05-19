<?php

declare(strict_types=1);

use App\FileInterface;
use App\FolderInterface;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddReturnTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/src']);

    $rectorConfig
        ->ruleWithConfiguration(AddReturnTypeDeclarationRector::class, [
            new AddReturnTypeDeclaration(
                FolderInterface::class,
                'create',
                TypeCombinator::addNull(new ObjectType(FileInterface::class)),
            ),
        ]);
};
