<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use Interop\Container\ContainerInterface;

/** @var ContainerInterface */
if ($container instanceof ContainerInterface) {
    $container->get('Foo');
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use Psr\Container\ContainerInterface;

/** @var ContainerInterface */
if ($container instanceof ContainerInterface) {
    $container->get('Foo');
}

?>
