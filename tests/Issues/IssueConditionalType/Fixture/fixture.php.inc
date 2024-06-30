<?php

namespace Rector\Tests\Issues\IssueConditionalType\Fixture;

class Fixture
{
    /**
     * @template TValue of array<array|scalar>|scalar
     *
     * @return (TValue is scalar ? array|scalar : array<array|scalar>)
     */
    public function resolveValue(): mixed
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\IssueConditionalType\Fixture;

class Fixture
{
    /**
     * @template TValue of array<array|scalar>|scalar
     *
     * @return (TValue is scalar ? array|scalar : array<array|scalar>)
     */
    public function resolveValue()
    {
    }
}

?>
