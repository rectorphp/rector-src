<?php

final class SomeStartWith
{
    public function run($a)
    {
        return strpos($a, 'a') === 0;
    }
}
