<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\String_\VariableInStringInterpolationFixerRector\Fixture;

$c = "football";
echo 'I like playing \${c}';
echo 'I like playing ${c}';
