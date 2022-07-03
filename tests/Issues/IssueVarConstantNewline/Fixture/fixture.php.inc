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
<<<<<<< HEAD
=======
    /**
     * @var string
     */
>>>>>>> [CodeQuality] Fix CompactToVariablesRector to resolve values when the compact() is called
    public const B = 'b';

    /**
     * @var string
     */
    public const D = 'd';
}

?>
