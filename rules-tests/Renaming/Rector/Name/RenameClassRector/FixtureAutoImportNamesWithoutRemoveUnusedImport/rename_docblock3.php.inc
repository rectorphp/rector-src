<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use Interop\Container\ContainerInterface;

if ($container instanceof ContainerInterface) {
    /** @var ContainerInterface */
    $container->get('Foo');
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use Psr\Container\ContainerInterface;

if ($container instanceof ContainerInterface) {
    /** @var ContainerInterface */
    $container->get('Foo');
}

?>
