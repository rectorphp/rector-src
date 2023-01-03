<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class PreserveMultipleDocsNoChange
{
    // A comment

    /**
     * Another comment
     */

    /**
     * @var class-string
     */
    private $property;

    public function __construct()
    {
        $this->property = 'DateTime';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class PreserveMultipleDocsNoChange
{
    // A comment

    /**
     * Another comment
     */

    /**
     * @var class-string
     */
    private string $property;

    public function __construct()
    {
        $this->property = 'DateTime';
    }
}

?>
