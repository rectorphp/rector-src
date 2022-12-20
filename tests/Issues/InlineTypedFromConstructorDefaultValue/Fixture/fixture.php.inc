<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\InlineTypedFromConstructorDefaultValue\Fixture;

final class Fixture
{
    private $url;

    public function __construct()
    {
        $this->url = 'https://website.tld';
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\InlineTypedFromConstructorDefaultValue\Fixture;

final class Fixture
{
    private string $url = 'https://website.tld';

    public function __construct()
    {
    }
}

?>
