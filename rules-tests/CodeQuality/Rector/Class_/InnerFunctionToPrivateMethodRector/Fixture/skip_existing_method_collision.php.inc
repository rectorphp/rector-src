<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InnerFunctionToPrivateMethodRector\Fixture;

class CollisionClass
{
    public function run(): void
    {
        function existing(): void
        {
            echo 'inner';
        }

        existing();
    }

    private function existing(): void
    {
        echo 'method';
    }
}
