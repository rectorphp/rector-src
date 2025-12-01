<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\FixtureSkipReturnAbstractOrInterface;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\AbstractTalk;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConcreteConferenceTalk;

final class SkipReturnAbstractClass
{
    public function create(): AbstractTalk
    {
        return new ConcreteConferenceTalk();
    }
}

?>