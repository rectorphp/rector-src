<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use PDO;

class SkipUsedAsPassByReferenceFalsy
{
    public function __construct(
        private int $id
    ) {
    }

    public function prepare(): void
    {
        $dsn = 'mysql:dbname=testdb;host=127.0.0.1';
        $user = 'dbuser';
        $password = 'dbpass';

        $dbh = new PDO($dsn, $user, $password);
        $sth = $dbh->prepare('SELECT * FROM users WHERE id = :id');
        $sth->bindParam('id', $this->id, PDO::PARAM_INT);
        $sth->execute();
    }
}