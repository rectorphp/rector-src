<?php

namespace Rector\Tests\PostRector\AdditionalPostRector\Fixture;

class RemoveComments
{
    /**
     * @param \DateTime $dateTime
     */
    public function runA($dateTime)
    {
    }

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function runB(\DateTimeInterface $dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\PostRector\AdditionalPostRector\Fixture;

use DateTimeInterface;

class RemoveComments
{
    public function runA($dateTime)
    {
    }

    public function runB(DateTimeInterface $dateTime)
    {
    }
}

?>
