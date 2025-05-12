<?php

enum E: string
{
    case E = 'e';
}

trait T
{        
    private function f(string $s): string
    {
        return mb_substr($s, mb_strlen(self::CASE->value) + 1);
    }
}

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

class C
{
    use T;
    
    private const E CASE = E::E;
}
?>
-----
<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

// what is expected code?
// should remain the same? delete part below ----- (included)

?>
