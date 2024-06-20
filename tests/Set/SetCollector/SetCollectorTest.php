<?php

declare(strict_types=1);

namespace Rector\Tests\Set\SetCollector;

use PHPUnit\Framework\TestCase;
use Rector\Set\SetCollector;
use Rector\Tests\Set\SetCollector\Source\SomeSetProvider;

final class SetCollectorTest extends TestCase
{
    public function test(): void
    {
        $setCollector = new SetCollector([new SomeSetProvider()]);

        $twigComposerTriggeredSet = $setCollector->matchComposerTriggered('twig');
        $this->assertCount(1, $twigComposerTriggeredSet);
    }
}
