<?php

declare(strict_types=1);

namespace Rector\Tests\Renaming\Rector\Name\RenameClassRector;

use Rector\Exception\Configuration\InvalidConfigurationException;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class SameOldToNewClassTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        $renameClassRector = $this->make(RenameClassRector::class);

        $this->expectException(InvalidConfigurationException::class);

        $renameClassRector->configure([
            'App\SomeClass' => 'App\SomeClass',
        ]);
    }
}
