<?php

class SomeClass
{
    public function setObjectProperty(string $object_property): void
    {

    }
}

?>
-----
<?php

class SomeClass
{
    public function setObjectProperty(string $objectProperty): void
    {

    }
}

?>
