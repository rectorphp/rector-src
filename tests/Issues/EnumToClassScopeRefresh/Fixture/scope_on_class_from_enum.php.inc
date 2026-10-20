<?php

namespace Rector\Tests\Issues\EnumToClassScopeRefresh\Fixture;

enum ScopeOnClassFromEnum
{
    case Char;
    case Word;
}

?>
-----
<?php

namespace Rector\Tests\Issues\EnumToClassScopeRefresh\Fixture;

class ScopeOnClassFromEnum
{
    public const Char = 'char';
    public const Word = 'word';
}

?>
