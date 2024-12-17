<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\CallUserFuncToMethodCallRector\Fixture;

abstract class PassArg
{
    public array $args;

    public function __construct(array $args = [])
    {
        if ($args !== [] && method_exists($this, 'args')) {
            $this->args = call_user_func([$this, 'args'], $args);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\CallUserFuncToMethodCallRector\Fixture;

abstract class PassArg
{
    public array $args;

    public function __construct(array $args = [])
    {
        if ($args !== [] && method_exists($this, 'args')) {
            $this->args = $this->args($args);
        }
    }
}

?>
