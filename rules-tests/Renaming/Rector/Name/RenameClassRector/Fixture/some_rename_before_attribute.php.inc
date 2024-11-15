<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Doctrine\DBAL\DBALException;
use Symfony\Component\Routing\Annotation\Route;

class SomeRenameBeforeAttribute
{
    /**
     * @throws DBALException
     */
    #[Route(path: '/top/', name: 'top')]
    public function testWithRouteAttribute(){
        // something throws the exception deep down here....
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector\Fixture;

use Symfony\Component\Routing\Annotation\Route;

class SomeRenameBeforeAttribute
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route(path: '/top/', name: 'top')]
    public function testWithRouteAttribute(){
        // something throws the exception deep down here....
    }
}

?>
