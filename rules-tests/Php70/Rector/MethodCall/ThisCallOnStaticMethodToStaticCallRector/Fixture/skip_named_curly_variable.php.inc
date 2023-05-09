<?php

namespace Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture;

final class SkipNamedCurlyVariable
{
    public static function macro()
    {
    }

    public function run()
    {
        $macros = ['whateverName'];
        $attributes = ['attribute1' => 'test', 'whateverName' => 'macro code example'];

        foreach (array_keys($macros) as $macro) {
            if (isset($attributes[$macro])) {
                is_array($attributes[$macro]) ? $this->{$macro}($attributes[$macro]) : $this->{$macro}([]);
                continue;
            }
        }
    }
}
