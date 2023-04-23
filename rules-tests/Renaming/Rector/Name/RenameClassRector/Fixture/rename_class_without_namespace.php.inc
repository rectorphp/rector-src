<?php

class MyOldClass
{
    /**
     * @return MyOldClass
     */
    public function createSelf(): MyOldClass
    {
        return new MyOldClass;
    }
}

?>
-----
<?php

namespace MyNamespace;

class MyNewClass2
{
    /**
     * @return \MyNamespace\MyNewClass2
     */
    public function createSelf(): \MyNamespace\MyNewClass2
    {
        return new \MyNamespace\MyNewClass2;
    }
}

?>
