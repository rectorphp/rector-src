<?php

final class GotUnreachableReturn
{
    public function run()
    {
        throw new Exception();

        return 'test';
    }
}
