<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class ParseUrlWithSecondArg
{
    private $host = null;

    public function __construct(string $url)
    {
        $this->host = parse_url($url, \PHP_URL_HOST);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class ParseUrlWithSecondArg
{
    private string|false|null $host = null;

    public function __construct(string $url)
    {
        $this->host = parse_url($url, \PHP_URL_HOST);
    }
}

?>
