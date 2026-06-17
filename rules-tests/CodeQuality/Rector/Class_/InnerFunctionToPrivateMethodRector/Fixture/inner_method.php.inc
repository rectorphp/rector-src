<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector\Fixture;

class SomeClass
{
    public function run(): void
    {
        function inner(): void
        {
            echo 'hello';
        }

        inner();
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector\Fixture;

class SomeClass
{
    public function run(): void
    {
        $this->inner();
    }
    private function inner(): void
    {
        echo 'hello';
    }
}

?>
