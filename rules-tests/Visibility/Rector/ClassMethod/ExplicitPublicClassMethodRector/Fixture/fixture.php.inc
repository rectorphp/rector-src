<?php

abstract class SomeAbstractClass
{
    abstract function noExplicitVis();

    abstract public function publicVis();

    abstract protected function protectedVis();

    abstract private function privateVis();

    static function noExplicitVisStatic()
    {
    }

    public static function publicVisStatic()
    {
    }
    
    protected static function protectedVisStatic()
    {
    }
    
    private static function privateVisStatic()
    {
    }
}

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

    final function noExplicitVisFinal()
    {
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

abstract class SomeAbstractClass
{
    abstract public function noExplicitVis();

    abstract public function publicVis();

    abstract protected function protectedVis();

    abstract private function privateVis();

    public static function noExplicitVisStatic()
    {
    }

    public static function publicVisStatic()
    {
    }
    
    protected static function protectedVisStatic()
    {
    }
    
    private static function privateVisStatic()
    {
    }
}

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

    final public function noExplicitVisFinal()
    {
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
