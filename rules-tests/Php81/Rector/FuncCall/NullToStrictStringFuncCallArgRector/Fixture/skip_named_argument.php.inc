<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NamedArgument
{
    public function run($subject)
    {
        preg_split(subject: $subject, pattern: '#a#');
    }
}

?>
