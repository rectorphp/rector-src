<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryImplodeToImplodeRector\Fixture;

class Fixture
{
    public function run(array $values)
    {
        return $values === [] ? '' : implode('...', $values);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryImplodeToImplodeRector\Fixture;

class Fixture
{
    public function run(array $values)
    {
        return implode('...', $values);
    }
}

?>
