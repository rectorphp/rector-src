<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class AnonymousExtendsExistingClass
{
    private $x;

    public function __construct()
    {
        $this->x = new class extends \DateTime {};
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class AnonymousExtendsExistingClass
{
    private \DateTime $x;

    public function __construct()
    {
        $this->x = new class extends \DateTime {};
    }
}

?>
