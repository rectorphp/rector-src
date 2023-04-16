<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class IfElseElseIfAssignInConstructor
{
    private string $env;

    public function __construct(bool $sandbox)
    {
        if ($sandbox) {
            $this->env = 'sandbox';
        } elseif (rand(0,1)) {
            $this->env = 'stage';
        } else {
            $this->env = 'prod';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class IfElseElseIfAssignInConstructor
{
    private readonly string $env;

    public function __construct(bool $sandbox)
    {
        if ($sandbox) {
            $this->env = 'sandbox';
        } elseif (rand(0,1)) {
            $this->env = 'stage';
        } else {
            $this->env = 'prod';
        }
    }
}

?>
