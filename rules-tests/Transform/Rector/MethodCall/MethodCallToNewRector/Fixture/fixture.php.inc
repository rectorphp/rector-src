<?php

namespace Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Fixture;

class Fixture
{
    public function run(\Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\ResponseFactory $factory)
    {
        $response = $factory->createResponse([
            'a' => 'b'
        ]);
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Fixture;

class Fixture
{
    public function run(\Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\ResponseFactory $factory)
    {
        $response = new \Rector\Tests\Transform\Rector\MethodCall\MethodCallToNewRector\Source\Response([
            'a' => 'b'
        ]);
    }
}

?>
