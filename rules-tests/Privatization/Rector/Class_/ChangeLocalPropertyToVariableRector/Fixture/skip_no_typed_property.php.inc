<?php

final class SkipNoTypedProperty
{
    private $name;

    public function sayHello()
    {
        return "Hello {$this->name}";
    }
}