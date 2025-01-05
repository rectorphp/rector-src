<?php

class SingleMethodClass
{
    public function __construct(private readonly \EventDispatcherInterface $eventDispatcher)
    {
    }
    function firstMethod()
    {
    }
}
