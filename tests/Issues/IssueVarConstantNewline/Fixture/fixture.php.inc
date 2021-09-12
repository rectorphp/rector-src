<?php

namespace Rector\Core\Tests\Issues\IssueVarConstantNewline\Fixture;

class Fixture
{
    public const A = 'a';

    public const B = 'b';
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\IssueVarConstantNewline\Fixture;

class Fixture
{
    /**
     * @var string
     */
    public const A = 'a';

    /**
     * @var string
     */
    public const B = 'b';
}

?>
