<?php

namespace Rector\Tests\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector\Fixture;

class SomeClass
{
    function noExplicitVis()
    {
        $a = 3;
        $closure = function ($b) use ($a) {
            return $a + $b;
        };
        return $closure;
    }

    public function publicVis()
    {
    }

    protected function protectedVis()
    {
    }

    private function privateVis()
    {
    }
}

function notInScope()
{
}

?>
-----
<?php

namespace Rector\Tests\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector\Fixture;

class SomeClass
{
    public function noExplicitVis()
    {
        $a = 3;
        $closure = function ($b) use ($a) {
            return $a + $b;
        };
        return $closure;
    }

    public function publicVis()
    {
    }

    protected function protectedVis()
    {
    }

    private function privateVis()
    {
    }
}

function notInScope()
{
}

?>
