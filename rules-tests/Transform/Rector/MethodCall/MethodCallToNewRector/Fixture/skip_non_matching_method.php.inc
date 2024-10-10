<?php

namespace Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Fixture;

class SkipNonMatchingMethod
{
    public function run(\Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\ResponseFactory $factory)
    {
        $response = $factory->jsonResponse([
            'a' => 'b'
        ]);
    }
}

?>
