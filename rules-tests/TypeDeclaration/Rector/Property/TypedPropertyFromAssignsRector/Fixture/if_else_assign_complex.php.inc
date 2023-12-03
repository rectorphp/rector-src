<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class IfElseAssignComplex
{
    private $apiUrl;

    public function __construct()
    {
        if (rand(0,1)) {
            if (rand(0, 1)) {
                $this->apiUrl = 'https://example.com/';
            }
        } else {
            $this->apiUrl = 'https://another.example.com/';
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class IfElseAssignComplex
{
    private ?string $apiUrl = null;

    public function __construct()
    {
        if (rand(0,1)) {
            if (rand(0, 1)) {
                $this->apiUrl = 'https://example.com/';
            }
        } else {
            $this->apiUrl = 'https://another.example.com/';
        }
    }
}

?>
