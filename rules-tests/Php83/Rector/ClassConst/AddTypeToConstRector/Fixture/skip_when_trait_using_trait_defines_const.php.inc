<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\SomeTrait;

class SkipWhenTraitDefinesInterface
{
    use SomeTrait;

    public const STRING = 'something';
}

?>

