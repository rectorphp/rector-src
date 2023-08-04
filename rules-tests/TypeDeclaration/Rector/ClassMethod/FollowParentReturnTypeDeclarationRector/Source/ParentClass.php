<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\FollowParentReturnTypeDeclarationRector\Source;

abstract class ParentClass
{
    public abstract function getData(): array;
}
