<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use PhpParser\ParserFactory as NikicParserFactory;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\GitWrapper;

final class PropertyTypeWithOnlyComment
{
    /**
     * only comment
     */
    private GitWrapper $whatever;
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use PhpParser\ParserFactory as NikicParserFactory;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\GitWrapper;

final class PropertyTypeWithOnlyComment
{
    /**
     * only comment
     */
    private GitWrapper $gitWrapper;
}

?>
