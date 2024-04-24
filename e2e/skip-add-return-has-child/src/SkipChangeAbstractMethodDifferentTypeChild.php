<?php

namespace App;

class SkipChangeAbstractMethodDifferentTypeChild extends SkipChangeAbstractMethodDifferentType
{
    public function run(): string
    {
        return 'a';
    }
}
