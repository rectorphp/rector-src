<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Source;

trait TraitUsingEntityManager
{
    public function run()
    {
        $this->entityManager->flush();
    }
}
