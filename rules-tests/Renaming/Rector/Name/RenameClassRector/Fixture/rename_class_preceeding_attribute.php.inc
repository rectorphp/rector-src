<?php

use Doctrine\DBAL\DBALException;

class MyClass
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

class MyClass
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
