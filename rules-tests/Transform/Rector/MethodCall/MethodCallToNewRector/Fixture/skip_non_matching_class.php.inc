<?php

namespace Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Fixture;

class SkipNonMatchingClass
{
    public function run(Response $factory)
    {
        $response = $factory->jsonResponse([
            'a' => 'b'
        ]);
    }
}

?>
