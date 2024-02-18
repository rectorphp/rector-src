<?php

namespace Rector\Tests\Transform\Rector\ConstFetch\ConstFetchToClassConstFetchRector;

$x = CONTEXT_COURSE;
-----
<?php

namespace Rector\Tests\Transform\Rector\ConstFetch\ConstFetchToClassConstFetchRector;

$x = \core\context\course::LEVEL;
