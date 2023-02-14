<?php

namespace MyNamespace;

class MyClass
{
    public function createSelf(): MyClass
    {
        return new MyClass;
    }
}

?>
-----
<?php

namespace MyNewNamespace;

class MyNewClass
{
    public function createSelf(): \MyNewNamespace\MyNewClass
    {
        return new \MyNewNamespace\MyNewClass;
    }
}

?>
