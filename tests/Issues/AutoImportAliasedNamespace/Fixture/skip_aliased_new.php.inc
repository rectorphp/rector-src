<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Constraints as Assert;

final class SkipAliasedNew
{
    public function run(Assert\Choice $choice): Assert\NotBlank
    {
        $class = Assert\Choice::class;

        return new Assert\NotBlank();
    }
}
