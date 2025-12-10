<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

if ((true ? 1 : 0) != (true ? 1 : 0)) {

}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector\Fixture;

if ((true ? 1 : 0) !== (true ? 1 : 0)) {

}

?>
