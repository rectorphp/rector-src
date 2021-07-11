<?php
final class SomeClass
{
    private int $count;

    public function __construct()
    {
        $this->count = 123;
    }

    public function getCount():int
    {
        return $this->count;
    }
}
