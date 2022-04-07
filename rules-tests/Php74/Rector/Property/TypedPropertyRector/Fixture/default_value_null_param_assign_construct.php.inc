<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DefaultValueNullParamAssignConstruct
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = null)
    {
        $this->data = $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DefaultValueNullParamAssignConstruct
{
    /**
     * @var array
     */
    private ?array $data;

    public function __construct(array $data = null)
    {
        $this->data = $data;
    }
}

?>