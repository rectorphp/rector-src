<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class VarStaticInUnion extends \DateTime
{
    /**
     * @var DateTime|static|null
     */
    private $dateTime;
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class VarStaticInUnion extends \DateTime
{
    /**
     * @var DateTimeInterface|static|null
     */
    private $dateTime;
}

?>
