<?php

final class SomeChildB extends ParentClass
{
    public function SomeChildB()
    {
        $this->ParentClass();
    }
}

?>
-----
<?php

final class SomeChildB extends ParentClass
{
    public function __construct()
    {
        \ParentClass::__construct();
    }
}

?>
