<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class MultilineContinueNextTag
{
    /**
     * @param array|null $excludePattern Optional regex; classes matching this are excluded
     *                                   from the layer even when $pattern matches.
     * @param array $pattern
     * @param array $options
     * @phpstan-param list<string>|null $excludePattern
     * @phpstan-param list<string> $pattern
     * @phpstan-param array{
     *     strict: bool,
     * } $options
     * @return array
     * @phpstan-return list<string>
     */
    public function run(array $pattern, ?array $excludePattern, array $options): array
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class MultilineContinueNextTag
{
    /**
     * @param array|null $excludePattern Optional regex; classes matching this are excluded
     *                                   from the layer even when $pattern matches.
     * @param array $options
     * @phpstan-param list<string>|null $excludePattern
     * @param list<string> $pattern
     * @phpstan-param array{
     *     strict: bool,
     * } $options
     * @return list<string>
     */
    public function run(array $pattern, ?array $excludePattern, array $options): array
    {
    }
}

?>
