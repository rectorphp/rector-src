<?php

namespace Rector\Tests\Issues\IssuePropertyPromoRemoveDelegatingParent\Fixture;

use Rector\Tests\Issues\IssuePropertyPromoRemoveDelegatingParent\Source\SomeParentWithEmptyConstruct;

class Fixture extends SomeParentWithEmptyConstruct
{
    private string $prop;

    public function __construct(string $prop)
    {
        $this->prop = $prop;
        parent::__construct();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\IssuePropertyPromoRemoveDelegatingParent\Fixture;

use Rector\Tests\Issues\IssuePropertyPromoRemoveDelegatingParent\Source\SomeParentWithEmptyConstruct;

class Fixture extends SomeParentWithEmptyConstruct
{
    public function __construct(private string $prop)
    {
        parent::__construct();
    }
}

?>
