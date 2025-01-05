<?php

class ClassWithMethodAndProperty
{
    private $someProperty;
    public function __construct(private readonly \EventDispatcherInterface $eventDispatcher)
    {
    }
    function someMethod()
    {
    }
}
