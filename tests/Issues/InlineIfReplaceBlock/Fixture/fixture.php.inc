<?php

namespace Rector\Tests\Issues\InlineIfReplaceBlock\Fixture;

{
	is_null($userId) && $userId = 5;

    {}
}

{
	is_null($userId) && $userId = 5;

    {}
}

?>
-----
<?php

namespace Rector\Tests\Issues\InlineIfReplaceBlock\Fixture;

if (is_null($userId)) {
    $userId = 5;
}

if (is_null($userId)) {
    $userId = 5;
}

?>
