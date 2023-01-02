<?php

/** No namespace on purpose to simplify demo that case insensitive string should not be marked as class-string */
class ClassStringCaseInsensitive
{
    public function set()
    {
        $this->value = 'classStringCaseInSensitive';
    }
}

?>
-----
<?php

/** No namespace on purpose to simplify demo that case insensitive string should not be marked as class-string */
class ClassStringCaseInsensitive
{
    /**
     * @var string
     */
    public $value;
    public function set()
    {
        $this->value = 'classStringCaseInSensitive';
    }
}

?>
