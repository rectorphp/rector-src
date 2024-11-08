<?php

class ConstantProperties
{
    const SOME_CONST = 'value';
    public $someProperty;
    public $anotherProperty;
    public function __construct(private \EventDispatcherInterface $eventDispatcher)
    {
    }
}
