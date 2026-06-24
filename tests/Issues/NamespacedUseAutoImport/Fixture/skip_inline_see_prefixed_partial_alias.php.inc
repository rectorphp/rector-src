<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Tests\Issues\NamespacedUseAutoImport as Alias;

final class SkipInlineSeePrefixedPartialAlias
{
    /**
     * See {@see Alias\Source\SomeClass} for more information.
     */
    public function test(): void {}
}
