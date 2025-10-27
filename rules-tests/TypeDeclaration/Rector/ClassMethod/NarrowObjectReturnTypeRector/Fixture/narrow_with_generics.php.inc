<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\AbstractGenericEntityFactory;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Speaker;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

/**
 * @extends AbstractGenericEntityFactory<Speaker>
 */
final class SpeakerFactory extends AbstractGenericEntityFactory
{
    protected function build(): object
    {
        return new Speaker();
    }
}

final class NarrowWithGenerics
{
    private SpeakerFactory $speakerFactory;

    public function __construct()
    {
        $this->speakerFactory = new SpeakerFactory();
    }

    public function createTalk(): object
    {
        $speaker = $this->speakerFactory->create();

        return $speaker->createTalk();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\AbstractGenericEntityFactory;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Speaker;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk;

/**
 * @extends AbstractGenericEntityFactory<Speaker>
 */
final class SpeakerFactory extends AbstractGenericEntityFactory
{
    protected function build(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Speaker
    {
        return new Speaker();
    }
}

final class NarrowWithGenerics
{
    private SpeakerFactory $speakerFactory;

    public function __construct()
    {
        $this->speakerFactory = new SpeakerFactory();
    }

    public function createTalk(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\ConferenceTalk
    {
        $speaker = $this->speakerFactory->create();

        return $speaker->createTalk();
    }
}

?>