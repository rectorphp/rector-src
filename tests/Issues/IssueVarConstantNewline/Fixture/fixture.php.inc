<?php

namespace Rector\Core\Tests\Issues\IssueVarConstantNewline\Fixture;

class Fixture
{
    public const B = 'b';
    public const D = 'd';
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueVarConstantNewline\Fixture;

class Fixture
{
    public const B = 'b';

    /**
     * @var string
     */
    public const D = 'd';
}

?>
