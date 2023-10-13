<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

class SkipMagicPropertyFetch
{
    public $x;
}

$f = new SkipMagicPropertyFetch();
$p = 'x';
isset($f->{$p});
