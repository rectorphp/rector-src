<?php

namespace Rector\Core\Tests\Issues\Issue6861\Fixture;

use Doctrine\DBAL\DBALException;

class Fixture
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

namespace Rector\Core\Tests\Issues\Issue6861\Fixture;

class Fixture
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
