<?php

class SomeClass
{
    private string $object_property = 'test';
    private static int $static_property = 1;

    public function printProperties(): string
    {
        return $this->object_property . '-' . self::$static_property;
    }
}

?>
-----
<?php

class SomeClass
{
    private string $objectProperty = 'test';
    private static int $staticProperty = 1;

    public function printProperties(): string
    {
        return $this->objectProperty . '-' . self::$staticProperty;
    }
}

?>
