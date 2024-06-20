<?php

declare(strict_types=1);

namespace Rector\Tests\Set\SetManager;

use PHPUnit\Framework\TestCase;
use Rector\Set\SetManager;
use Rector\Tests\Set\SetManager\Source\SomeSetProvider;

final class SetManagerTest extends TestCase
{
    public function test(): void
    {
        $setManager = new SetManager([new SomeSetProvider()]);

        $twigComposerTriggeredSet = $setManager->matchComposerTriggered('twig');
        $this->assertCount(1, $twigComposerTriggeredSet);
    }
}
