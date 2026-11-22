<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class PartialApplication
{
    private $data;

    public function __construct()
    {
        $this->data = array_map(trim(?), [' x ']);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class PartialApplication
{
    private array $data;

    public function __construct()
    {
        $this->data = array_map(trim(?), [' x ']);
    }
}

?>