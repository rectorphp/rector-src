<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class DifferentVariableNameUsed
{
    /**
     * @var int
     */
    private $ID;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->ID = $id;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class DifferentVariableNameUsed
{
    /**
     * @param int $ID
     */
    public function __construct(private $ID)
    {
    }
}

?>
