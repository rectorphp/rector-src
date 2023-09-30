<?php

// PHP4 code is not allowed to contain namespaces

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

// PHP4 code is not allowed to contain namespaces

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
