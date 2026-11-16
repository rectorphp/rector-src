<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class SkipPublicAsymmetricVisibility
{
    public protected(set) string $name = 'a';

    protected(set) string $other = 'b';

    public function __construct(
        public protected(set) int $promoted,
    ) {
    }
}
