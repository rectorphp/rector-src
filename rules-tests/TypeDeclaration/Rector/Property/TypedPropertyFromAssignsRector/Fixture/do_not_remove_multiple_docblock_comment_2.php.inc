<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class DoNotRemoveMultipleDocblockComment2
{
    // A comment

    /**
     * Another comment
     */

    /**
     * Another comment inside main doc
     *
     * @var \DateTime
     */
    private $property;

    public function __construct()
    {
        $this->property = new \DateTime();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class DoNotRemoveMultipleDocblockComment2
{
    // A comment
    /**
     * Another comment
     */
    /**
     * Another comment inside main doc
     */
    private \DateTime $property;

    public function __construct()
    {
        $this->property = new \DateTime();
    }
}

?>
