<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Fixture;

use Doctrine\DBAL\Connection;

final class SkipFetchFirstColumn
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        return $this->connection->fetchFirstColumn();
    }
}
