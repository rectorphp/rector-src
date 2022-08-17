<?php

class SomeClass
{
    private static int $static_property;

    public function setStaticProperty(string $static_property): void
    {
        self::$static_property = $static_property;
    }
}

?>
-----
<?php

class SomeClass
{
    private static int $staticProperty;

    public function setStaticProperty(string $staticProperty): void
    {
        self::$staticProperty = $staticProperty;
    }
}

?>
