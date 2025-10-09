<?php

/** No namespace on purpose to simplify demo that case sensitive string should be marked as class-string */
class ClassStringCaseSensitive
{
    public function set()
    {
        $this->value = 'ClassStringCaseSensitive';
    }
}

?>
-----
<?php

/** No namespace on purpose to simplify demo that case sensitive string should be marked as class-string */
class ClassStringCaseSensitive
{
    /**
     * @var class-string<\ClassStringCaseSensitive>
     */
    public $value;
    public function set()
    {
        $this->value = 'ClassStringCaseSensitive';
    }
}

?>
