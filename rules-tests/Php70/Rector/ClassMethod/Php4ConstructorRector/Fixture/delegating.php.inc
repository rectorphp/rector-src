<?php

declare(strict_types=1);

namespace Rector\Tests\Php70\Rector\ClassMethod\Php4ConstructorRector\Fixture;

final class DelegatingPhp4ConstructorClass
{
    /**
     * Constructor
     */
    public function __construct()
    {
        var_dump(1);
    }

    /**
     * PHP-4 Constructor
     */
    function DelegatingPhp4ConstructorClass()
    {
        // Call PHP5!
        $this->__construct();
    }

    public function bar()
    {
        var_dump(3);
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php70\Rector\ClassMethod\Php4ConstructorRector\Fixture;

final class DelegatingPhp4ConstructorClass
{
    /**
     * Constructor
     */
    public function __construct()
    {
        var_dump(1);
    }

    public function bar()
    {
        var_dump(3);
    }
}

?>
