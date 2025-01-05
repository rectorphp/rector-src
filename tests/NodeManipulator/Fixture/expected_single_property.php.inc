<?php

class ClassWithSingleProperty
{
    private $someProperty;
    public function __construct(private readonly \EventDispatcherInterface $eventDispatcher)
    {
    }
}
