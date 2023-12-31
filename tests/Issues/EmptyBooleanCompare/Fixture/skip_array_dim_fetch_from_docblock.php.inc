<?php

namespace Rector\Tests\Issues\EmptyBooleanCompare\Fixture;

final class SkipArrayDimFetchFromDocblock
{
    /**
     * @param array{host: string} $parts
     */
    public function checkUrl(array $parts)
    {
        if (!empty($parts['host'])) {
            return $parts['host'];
        }

        return null;
    }
}
