<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Talk;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalkExtended;

abstract class AbstractTalkFactory
{
    abstract public function build(): Talk;
}

final class ConcreteTalkFactory extends AbstractTalkFactory
{
    public function build(): Talk
    {
        return new ConferenceTalkExtended();
    }
}

?>