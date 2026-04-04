<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Fixture;

json_decode($json, true, 512, JSON_THROW_ON_ERROR);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector\Fixture;

json_decode($json, associative: true, depth: 512, flags: JSON_THROW_ON_ERROR);

?>
