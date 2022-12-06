<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class AnonymousExtendsExistingClassInUnionRemoveDocblock
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
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class AnonymousExtendsExistingClassInUnion
{
    private \DateTime|null $x = null;

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
