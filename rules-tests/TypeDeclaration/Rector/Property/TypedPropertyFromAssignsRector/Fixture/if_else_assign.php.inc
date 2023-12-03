<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class IfElseAssign
{
    private $apiUrl;

    public function __construct()
    {
        if (rand(0,1)) {
            $this->apiUrl = 'https://example.com/';
        } else {
            $this->apiUrl = 'https://another.example.com/';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class IfElseAssign
{
    private string $apiUrl;

    public function __construct()
    {
        if (rand(0,1)) {
            $this->apiUrl = 'https://example.com/';
        } else {
            $this->apiUrl = 'https://another.example.com/';
        }
    }
}

?>
