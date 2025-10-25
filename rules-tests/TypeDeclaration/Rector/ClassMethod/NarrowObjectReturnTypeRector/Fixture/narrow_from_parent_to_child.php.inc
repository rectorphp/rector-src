<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class Talk
{
}

class ConferenceTalkExtended extends Talk
{
}

final class TalkFactoryExtended
{
    public function createConferenceTalk(): Talk
    {
        return new ConferenceTalkExtended();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

class Talk
{
}

class ConferenceTalkExtended extends Talk
{
}

final class TalkFactoryExtended
{
    public function createConferenceTalk(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture\ConferenceTalkExtended
    {
        return new ConferenceTalkExtended();
    }
}

?>
