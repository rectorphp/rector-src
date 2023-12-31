<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InlineTypedFromConstructorDefaultValue\Fixture;

final class InlinedTypedProperty
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

namespace Rector\Tests\Issues\InlineTypedFromConstructorDefaultValue\Fixture;

final class InlinedTypedProperty
{
    private string $url = 'https://website.tld';

    public function __construct()
    {
    }
}

?>
