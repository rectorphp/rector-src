<?php

namespace Rector\Tests\Issues\EmptyBooleanCompare\Fixture;

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

namespace Rector\Tests\Issues\EmptyBooleanCompare\Fixture;

final class SomeFixture
{
    public function checkUrl(string $url)
    {
        $parts = parse_url($url);

        if (isset($parts['host']) && ($parts['host'] !== '' && $parts['host'] !== '0')) {
            return $parts['host'];
        }

        return null;
    }
}

?>
