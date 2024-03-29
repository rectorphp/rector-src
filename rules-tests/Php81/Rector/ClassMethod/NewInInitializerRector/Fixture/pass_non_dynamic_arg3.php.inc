<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

class SomeClass
{
    private Logger $logger;

    public function __construct(
        ?Logger $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger(['b']);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

class SomeClass
{
    public function __construct(private Logger $logger = new NullLogger(['b']))
    {
    }
}

?>
