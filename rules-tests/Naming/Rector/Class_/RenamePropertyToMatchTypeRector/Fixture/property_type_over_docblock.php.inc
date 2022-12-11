<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use PhpParser\ParserFactory as NikicParserFactory;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\GitWrapper;

final class PropertyTypeOverDocblock
{
    /**
     * @var NikicParserFactory
     */
    private GitWrapper $whatever;
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use PhpParser\ParserFactory as NikicParserFactory;
use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\GitWrapper;

final class PropertyTypeOverDocblock
{
    /**
     * @var NikicParserFactory
     */
    private GitWrapper $gitWrapper;
}

?>
