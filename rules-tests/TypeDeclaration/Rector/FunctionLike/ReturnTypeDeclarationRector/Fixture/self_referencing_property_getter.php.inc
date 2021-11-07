<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\Fixture;

class SelfReferencingPropertyGetter
{
    private ?self $parent = null;

    public function getParent(): ?self
    {
        return $this->parent;
    }
}
?>
