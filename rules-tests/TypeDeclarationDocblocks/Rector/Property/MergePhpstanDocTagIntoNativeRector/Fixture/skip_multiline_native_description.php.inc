<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class SkipMultilineNativeDescription
{
    /**
     * @param string|array|null $excludePattern Optional regex; classes matching this are excluded
     *                                          from the layer even when $pattern matches.
     * @phpstan-param string|list<string|null>|null $excludePattern
     */
    public function layerPattern(string $name, string|array $pattern, string|array|null $excludePattern = null): self
    {
    }
}
