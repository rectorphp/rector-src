<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class KeepNativeDescription
{
    /**
     * @param string|array|null $excludePattern Optional regex; classes matching this are excluded
     *                                          from the layer even when $pattern matches.
     * @phpstan-param string|list<string|null>|null $excludePattern
     */
    public function layerPattern(string $name, string|array $pattern, string|array|null $excludePattern = null): self
    {
    }

    /**
     * @return array some items
     * @phpstan-return list<string>
     */
    public function items(): array
    {
    }

    /**
     * @var array some values
     * @phpstan-var list<string>
     */
    private $values;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class KeepNativeDescription
{
    /**
     * @param string|list<string|null>|null $excludePattern Optional regex; classes matching this are excluded
     *                                          from the layer even when $pattern matches.
     */
    public function layerPattern(string $name, string|array $pattern, string|array|null $excludePattern = null): self
    {
    }

    /**
     * @return list<string> some items
     */
    public function items(): array
    {
    }

    /**
     * @var list<string> some values
     */
    private $values;
}

?>
