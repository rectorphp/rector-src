<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class MatchChangedVariable
{
    /**
     * @var float
     */
    public $x;

    public function __construct(float $a = 0.0)
    {
        $this->x = $a;
    }

    public function useProperty()
    {
        return $this->x;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class MatchChangedVariable
{
    public function __construct(public float $a = 0.0)
    {
    }

    public function useProperty()
    {
        return $this->a;
    }
}

?>
