<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\SomeTrait;

trait SkipWhenTraitDefinesInterface
{
    use SomeTrait;

    public const STRING = 'something';
}

?>

