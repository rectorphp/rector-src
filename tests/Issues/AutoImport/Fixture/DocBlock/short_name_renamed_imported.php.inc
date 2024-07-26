<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

class ShortNameRenamedImported
{
    /**
     * @param \DateTime $dateTime
     */
    public function some($dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use DateTimeInterface;
class ShortNameRenamedImported
{
    /**
     * @param DateTimeInterface $dateTime
     */
    public function some($dateTime)
    {
    }
}

?>
