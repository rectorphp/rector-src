<?php

namespace Rector\Core\Tests\Issues\DowngradeNullJson\Fixture;

class Fixture
{
    public function run(?int $flags = null)
    {
        $flags ??= JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR;

        json_encode([], $flags);
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DowngradeNullJson\Fixture;

class Fixture
{
    public function run(?int $flags = null)
    {
        $flags = $flags ?? JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRESERVE_ZERO_FRACTION;

        json_encode([], $flags);
    }
}

?>
