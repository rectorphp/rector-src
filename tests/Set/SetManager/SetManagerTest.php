<?php

declare(strict_types=1);

namespace Rector\Tests\Set\SetManager;

use PHPUnit\Framework\TestCase;
use Rector\Bridge\SetProviderCollector;
use Rector\Set\Enum\SetGroup;
use Rector\Set\SetManager;
use Rector\Tests\Set\SetManager\Source\SomeSetProvider;

final class SetManagerTest extends TestCase
{
    public function test(): void
    {
        $setProviderCollector = new SetProviderCollector([new SomeSetProvider()]);

        $setManager = new SetManager($setProviderCollector);

        $twigComposerTriggeredSet = $setManager->matchComposerTriggered(SetGroup::TWIG);
        $this->assertGreaterThan(6, count($twigComposerTriggeredSet));
    }
}
