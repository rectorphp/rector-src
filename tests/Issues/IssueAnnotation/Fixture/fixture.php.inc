<?php

namespace Rector\Tests\Issues\IssueAnnotation\Fixture;

use Rector\Tests\Issues\IssueAnnotation\Source\SomeAttributes;

final class Anything extends SomeAttributes
{
    public function __construct()
    {
        parent::__construct(
            tags: ['some_items']
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\IssueAnnotation\Fixture;

use Rector\Tests\Issues\IssueAnnotation\Source\SomeAttributes;

final class Anything extends SomeAttributes
{
    public function __construct()
    {
        parent::__construct(
            ['some_items']
        );
    }
}

?>
