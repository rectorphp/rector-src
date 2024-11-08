<?php

class ClassWithMethodAndProperty
{
    private $someProperty;
    public function __construct(private \EventDispatcherInterface $eventDispatcher)
    {
    }
    function someMethod()
    {
    }
}
