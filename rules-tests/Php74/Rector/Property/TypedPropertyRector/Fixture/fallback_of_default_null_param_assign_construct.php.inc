<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class FallbackOfDefaultNullParamAssignConstruct
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ?? [];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class FallbackOfDefaultNullParamAssignConstruct
{
    private array $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ?? [];
    }
}

?>
