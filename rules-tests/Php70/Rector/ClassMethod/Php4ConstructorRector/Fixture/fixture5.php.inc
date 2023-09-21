<?php

namespace Rector\Tests\Php70\Rector\ClassMethod\Php4ConstructorRector\Fixture;

class FooParEnt
{
}

class Php4ConstructorWithParent extends FooParEnt
{
    /**
     * Constructor
     */
    function Php4ConstructorWithParent($bar)
    {
        parent::FooPaRent(1);
        var_dump(9);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\ClassMethod\Php4ConstructorRector\Fixture;

class FooParEnt
{
}

class Php4ConstructorWithParent extends FooParEnt
{
    /**
     * Constructor
     */
    function __construct($bar)
    {
        parent::__construct(1);
        var_dump(9);
    }
}

?>
