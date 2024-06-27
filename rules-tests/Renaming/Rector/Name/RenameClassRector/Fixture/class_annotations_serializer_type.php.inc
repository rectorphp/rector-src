<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use JMS\Serializer\Annotation as Serializer;

class ClassAnnotationsSerializerIterableType
{
    /**
     * @Serializer\Type("array<Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass>")
     */
    public $flights = [];

    /**
     * @Serializer\Type("Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\OldClass")
     */
    public $time;
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use JMS\Serializer\Annotation as Serializer;

class ClassAnnotationsSerializerIterableType
{
    /**
     * @Serializer\Type("array<Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass>")
     */
    public $flights = [];

    /**
     * @Serializer\Type("Rector\Tests\Renaming\Rector\Name\RenameClassRector\Source\NewClass")
     */
    public $time;
}

?>
