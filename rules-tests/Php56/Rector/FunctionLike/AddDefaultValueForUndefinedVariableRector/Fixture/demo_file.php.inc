<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

final class DemoFile
{   
    public function run(Item $yes)
    {
        while (true !== false) {
        }
        
        $a = [];
        
        if ($b = $yes) {
            $a[] = 'test';
        }
        
        return $a;
    }
}

?>
