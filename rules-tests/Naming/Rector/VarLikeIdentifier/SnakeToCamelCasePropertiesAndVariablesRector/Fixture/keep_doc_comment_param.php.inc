<?php

class SomeClass
{
    /**
     * @param string $test_param
     */
    public function set_object_property(string $test_param): void
    {

    }
}

?>
-----
<?php

class SomeClass
{
    /**
     * @param string $test_param
     */
    public function set_object_property(string $testParam): void
    {

    }
}

?>
