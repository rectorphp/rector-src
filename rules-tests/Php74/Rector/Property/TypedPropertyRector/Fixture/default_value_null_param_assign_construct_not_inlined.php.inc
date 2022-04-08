<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DefaultValueNullParamAssignConstructNotInlined
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = null)
    {
        if (rand(0, 1)) {
            $this->data = $data;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DefaultValueNullParamAssignConstructNotInlined
{
    /**
     * @var array
     */
    private ?array $data = null;

    public function __construct(array $data = null)
    {
        if (rand(0, 1)) {
            $this->data = $data;
        }
    }
}

?>
