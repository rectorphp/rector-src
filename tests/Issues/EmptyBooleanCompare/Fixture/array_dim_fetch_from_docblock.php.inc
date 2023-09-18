<?php

namespace Rector\Core\Tests\Issues\EmptyBooleanCompare\Fixture;

final class ArrayDimFetchFromDocblock
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

?>
-----
<?php

namespace Rector\Core\Tests\Issues\EmptyBooleanCompare\Fixture;

final class ArrayDimFetchFromDocblock
{
    /**
     * @param array{host: string} $parts
     */
    public function checkUrl(array $parts)
    {
        if (isset($parts['host']) && $parts['host'] !== '') {
            return $parts['host'];
        }

        return null;
    }
}

?>
