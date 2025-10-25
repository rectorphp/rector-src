<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class AbstractFactory
{
}

class ConferenceTalk
{
}

final class TalkFactory extends AbstractFactory
{
    protected function build(): object
    {
        return new ConferenceTalk();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class AbstractFactory
{
}

class ConferenceTalk
{
}

final class TalkFactory extends AbstractFactory
{
    protected function build(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture\ConferenceTalk
    {
        return new ConferenceTalk();
    }
}

?>