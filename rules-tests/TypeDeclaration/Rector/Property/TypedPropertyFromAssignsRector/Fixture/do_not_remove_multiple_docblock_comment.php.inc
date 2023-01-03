<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class DoNotRemoveMultipleDocblockComment
{
    // A comment

    /**
     * Another comment
     */

    /**
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

final class DoNotRemoveMultipleDocblockComment
{
    // A comment
    /**
     * Another comment
     */
    private \DateTime $property;

    public function __construct()
    {
        $this->property = new \DateTime();
    }
}

?>
