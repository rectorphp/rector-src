<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class CloneThis extends \DateTime
{
    /**
     * @return static
     */
    public function executeCloneThis(\DateTime $dateTime)
    {
        $obj = clone $this;
        return $obj;
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

class CloneThis extends \DateTime
{
    /**
     * @return static
     */
    public function executeCloneThis(\DateTimeInterface $dateTime)
    {
        $obj = clone $this;
        return $obj;
    }
}

?>
