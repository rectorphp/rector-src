<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Talk;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

final class NarrowFromMethodCall
{
    public function createTalk(): ConferenceTalk
    {
        return new ConferenceTalk();
    }

    public function build(): object
    {
        return $this->createTalk();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Talk;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

final class NarrowFromMethodCall
{
    public function createTalk(): ConferenceTalk
    {
        return new ConferenceTalk();
    }

    public function build(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk
    {
        return $this->createTalk();
    }
}

?>
