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

?>
