<?php

namespace Rector\Tests\DeadCode\Rector\Concat\RemoveConcatAutocastRector\Fixture;

class DoNotRemoveParentheses
{
    public function run($value)
    {
        return "Hello, " . (string)($value['name'] ?? 'World');
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Concat\RemoveConcatAutocastRector\Fixture;

class DoNotRemoveParentheses
{
    public function run($value)
    {
        return "Hello, " . ($value['name'] ?? 'World');
    }
}

?>
