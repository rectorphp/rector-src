<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector\Fixture;

final class SkipAlreadyStaticReturn
{
    public function transform(): static
    {
        return $this;
    }
}