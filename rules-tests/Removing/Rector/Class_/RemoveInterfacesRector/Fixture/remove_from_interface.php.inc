<?php

namespace Rector\Tests\Removing\Rector\Class_\RemoveInterfacesRector\Fixture;

use Rector\Tests\Removing\Rector\Class_\RemoveInterfacesRector\Source\SomeInterface;

interface RemoveFromInterface extends SomeInterface
{
}

?>
-----
<?php

namespace Rector\Tests\Removing\Rector\Class_\RemoveInterfacesRector\Fixture;

use Rector\Tests\Removing\Rector\Class_\RemoveInterfacesRector\Source\SomeInterface;

interface RemoveFromInterface
{
}

?>
