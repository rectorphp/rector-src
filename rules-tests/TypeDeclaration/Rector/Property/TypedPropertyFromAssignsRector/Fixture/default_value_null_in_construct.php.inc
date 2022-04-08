<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

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

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class DefaultValueNullParamAssignConstruct
{
    private ?array $data = null;

    public function __construct(array $data = null)
    {
        $this->data = $data;
    }
}

?>
