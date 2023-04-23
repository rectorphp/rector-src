<?php

namespace MyNamespace;

class MylegacyClass
{
    /**
     * @return MylegacyClass
     */
    public function createSelf(): MylegacyClass
    {
        return new MylegacyClass;
    }
}

?>
-----
<?php

namespace MyNewNamespace;

class MyNewClass
{
    /**
     * @return \MyNewNamespace\MyNewClass
     */
    public function createSelf(): \MyNewNamespace\MyNewClass
    {
        return new \MyNewNamespace\MyNewClass;
    }
}

?>
