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
<<<<<<< HEAD
=======
    /**
     * @var string
     */
>>>>>>> [CodeQuality] Fix CompactToVariablesRector to resolve values when the compact() is called
=======
>>>>>>> temporary skip the var constant + newline issue
    public const B = 'b';

    /**
     * @var string
     */
    public const D = 'd';
}

?>
