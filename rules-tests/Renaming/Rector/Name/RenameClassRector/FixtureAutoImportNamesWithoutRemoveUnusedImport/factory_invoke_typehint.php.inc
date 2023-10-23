<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use Interop\Container\ContainerInterface;

class FactoryInvokeTypehint
{
    public function __invoke(ContainerInterface $container)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\FixtureAutoImportNamesWithoutRemoveUnusedImport;

use Psr\Container\ContainerInterface;

class FactoryInvokeTypehint
{
    public function __invoke(ContainerInterface $container)
    {
    }
}

?>
