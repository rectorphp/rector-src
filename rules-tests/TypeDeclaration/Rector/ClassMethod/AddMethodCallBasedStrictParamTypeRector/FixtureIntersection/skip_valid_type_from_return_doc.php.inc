<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\FixtureIntersection;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Source\ConnectionInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Source\ConnectionResolverInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Source\SqlServerConnection;

final class SkipValidTypeFromReturnDoc
{
    public function __invoke(ConnectionResolverInterface $resolver): void
    {
        $connection = $resolver->connection();

        if (
            $connection instanceof SqlServerConnection
            || ! method_exists($connection, 'getSchemaState')
        ) {
            return;
        }

        $this->ensureCleanDatabase($connection);
    }

    private function ensureCleanDatabase(ConnectionInterface $connection): void
    {
    }
}
