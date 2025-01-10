<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Source;

trait TraitWithUnsetProperty
{
    public function reset()
    {
        unset($this->entityManager);
    }
}
