<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class IfElseAssignInConstructor
{
    private string $env;

    public function __construct(bool $sandbox)
    {
        if ($sandbox) {
            $this->env = 'sandbox';
        } else {
            $this->env = 'prod';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class IfElseAssignInConstructor
{
    private readonly string $env;

    public function __construct(bool $sandbox)
    {
        if ($sandbox) {
            $this->env = 'sandbox';
        } else {
            $this->env = 'prod';
        }
    }
}

?>
