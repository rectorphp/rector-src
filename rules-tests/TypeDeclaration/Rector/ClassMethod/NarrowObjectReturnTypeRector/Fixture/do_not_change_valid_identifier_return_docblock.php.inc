<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use DateTime;

final class DoNotChangeValidIdentifierReturnDocblock
{
    /**
     * @return DateTime
     */
    protected function build(): object
    {
        return new DateTime('now');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use DateTime;

final class DoNotChangeValidIdentifierReturnDocblock
{
    /**
     * @return DateTime
     */
    protected function build(): \DateTime
    {
        return new DateTime('now');
    }
}

?>