<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class AnonymousExtendsExistingClassInUnion
{
    private $x;

    public function __construct()
    {
        if (rand(0,1)) {
            $this->x = new \DateTime('now');
        } else {
            $this->x = new class extends \DateTime {};
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class AnonymousExtendsExistingClassInUnion
{
    /**
     * @var \DateTime|null
     */
    private $x;

    public function __construct()
    {
        if (rand(0,1)) {
            $this->x = new \DateTime('now');
        } else {
            $this->x = new class extends \DateTime {};
        }
    }
}

?>
