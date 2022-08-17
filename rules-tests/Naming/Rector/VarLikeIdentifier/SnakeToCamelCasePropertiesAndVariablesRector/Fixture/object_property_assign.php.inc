<?php

class SomeClass
{
    private string $object_property;

    public function setObjectProperty(string $object_property): void
    {
        $this->object_property = $object_property;
    }
}

?>
-----
<?php

class SomeClass
{
    private string $objectProperty;

    public function setObjectProperty(string $objectProperty): void
    {
        $this->objectProperty = $objectProperty;
    }
}

?>
