<?php

declare(strict_types=1);

final class SkipReservedClassName
{
    public function aMethod()
    {
        $foo = new \Cassandra\Float(123.45);
    }
}
