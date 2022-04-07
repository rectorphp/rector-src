<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

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

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

final class DefaultValueNullParamAssignConstruct
{
    /**
     * @var array
     */
    private ?array $data = null;

    public function __construct(array $data = null)
    {
        $this->data = $data;
    }
}

?>