<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\AbstractFactory;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

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

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\AbstractFactory;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

final class TalkFactory extends AbstractFactory
{
    protected function build(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk
    {
        return new ConferenceTalk();
    }
}

?>