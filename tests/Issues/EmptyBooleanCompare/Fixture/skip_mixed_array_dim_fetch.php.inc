<?php

namespace Rector\Core\Tests\Issues\EmptyBooleanCompare\Fixture;

final class SkipMixedArrayDimFetch
{
    public function checkUrl(array $parts)
    {
        if (!empty($parts['host'])) {
            return $parts['host'];
        }

        return null;
    }
}
