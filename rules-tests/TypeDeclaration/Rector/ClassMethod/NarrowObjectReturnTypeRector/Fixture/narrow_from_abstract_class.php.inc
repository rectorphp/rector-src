<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

abstract class AbstractTalk
{
}

final class ConcreteConferenceTalk extends AbstractTalk
{
}

final class TalkFactory
{
    public function create(): AbstractTalk
    {
        return new ConcreteConferenceTalk();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

abstract class AbstractTalk
{
}

final class ConcreteConferenceTalk extends AbstractTalk
{
}

final class TalkFactory
{
    public function create(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture\ConcreteConferenceTalk
    {
        return new ConcreteConferenceTalk();
    }
}

?>