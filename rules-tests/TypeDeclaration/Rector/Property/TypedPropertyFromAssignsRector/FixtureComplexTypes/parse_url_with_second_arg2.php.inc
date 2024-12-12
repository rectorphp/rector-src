<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class ParseUrlWithSecondArg2
{
    private $host = true;

    public function __construct(string $url)
    {
        $this->host = parse_url($url, \PHP_URL_HOST);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

final class ParseUrlWithSecondArg2
{
    private string|bool|null $host = true;

    public function __construct(string $url)
    {
        $this->host = parse_url($url, \PHP_URL_HOST);
    }
}

?>
