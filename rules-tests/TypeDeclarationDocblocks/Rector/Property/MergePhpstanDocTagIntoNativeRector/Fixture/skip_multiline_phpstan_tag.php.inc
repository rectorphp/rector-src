<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class SkipMultilinePhpstanTag
{
    /**
     * @var array
     *
     * @phpstan-var array{
     *     name: string,
     *     age: int,
     * }
     */
    private $items;

    /**
     * @param array $data
     *
     * @phpstan-param array{
     *     name: string,
     * } $data
     */
    public function run(array $data)
    {
    }
}
