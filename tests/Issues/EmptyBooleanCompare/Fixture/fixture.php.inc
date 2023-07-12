<?php

namespace Rector\Core\Tests\Issues\EmptyBooleanCompare\Fixture;

final class SomeFixture
{
    public function checkUrl(string $url)
    {
        $parts = parse_url($url);

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

final class SomeFixture
{
    public function checkUrl(string $url)
    {
        $parts = parse_url($url);

        if (isset($parts['host']) && $parts['host'] !== '') {
            return $parts['host'];
        }

        return null;
    }
}

?>
