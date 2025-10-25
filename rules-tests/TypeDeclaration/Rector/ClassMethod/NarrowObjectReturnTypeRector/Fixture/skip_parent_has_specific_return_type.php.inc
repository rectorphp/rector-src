<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class Talk
{
}

class ConferenceTalkChild extends Talk
{
}

abstract class AbstractTalkFactory
{
    abstract public function build(): Talk;
}

final class ConcreteTalkFactory extends AbstractTalkFactory
{
    public function build(): Talk
    {
        return new ConferenceTalkChild();
    }
}

?>