<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\SomeTrait;

final class SkipWhenTraitDefinesInterface
{
    use SomeTrait;

    public const STRING = 'something';
}

?>

