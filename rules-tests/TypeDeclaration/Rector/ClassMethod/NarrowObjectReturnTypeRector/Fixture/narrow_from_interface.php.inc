<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

interface TalkInterface
{
}

final class ConferenceTalkImplementation implements TalkInterface
{
}

final class TalkFactory
{
    public function create(): TalkInterface
    {
        return new ConferenceTalkImplementation();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

interface TalkInterface
{
}

final class ConferenceTalkImplementation implements TalkInterface
{
}

final class TalkFactory
{
    public function create(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture\ConferenceTalkImplementation
    {
        return new ConferenceTalkImplementation();
    }
}

?>