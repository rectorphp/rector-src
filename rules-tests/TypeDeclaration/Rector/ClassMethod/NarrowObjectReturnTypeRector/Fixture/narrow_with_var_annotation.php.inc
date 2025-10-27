<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Talk;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

final class NarrowWithVarAnnotation
{
    private function createTalk(): Talk
    {
        return new ConferenceTalk();
    }

    public function build(): object
    {
        /**
         * @var ConferenceTalk $talk
         */
        $talk = $this->createTalk();

        return $talk;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Talk;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

final class NarrowWithVarAnnotation
{
    private function createTalk(): Talk
    {
        return new ConferenceTalk();
    }

    public function build(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk
    {
        /**
         * @var ConferenceTalk $talk
         */
        $talk = $this->createTalk();

        return $talk;
    }
}

?>