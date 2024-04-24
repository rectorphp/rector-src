<?php

namespace App;

abstract class SkipChangeAbstractMethodDifferentType
{
    public function run()
    {
        return new static();
    }
}
