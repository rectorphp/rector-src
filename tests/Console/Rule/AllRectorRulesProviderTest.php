<?php

declare(strict_types=1);

namespace Rector\Tests\Console\Rule;

use PHPUnit\Framework\TestCase;
use Rector\Console\Rule\AllRectorRulesProvider;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\PostRector\Rector\UnusedImportRemovingPostRector;

final class AllRectorRulesProviderTest extends TestCase
{
    private AllRectorRulesProvider $allRectorRulesProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->allRectorRulesProvider = new AllRectorRulesProvider();
    }

    public function testProvidesCoreRule(): void
    {
        $rectorClasses = $this->allRectorRulesProvider->provide();

        $this->assertContains(RemoveDoubleAssignRector::class, $rectorClasses);
    }

    public function testSkipsPostRectors(): void
    {
        $rectorClasses = $this->allRectorRulesProvider->provide();

        $this->assertNotContains(UnusedImportRemovingPostRector::class, $rectorClasses);
    }

    public function testListIsSortedAndUnique(): void
    {
        $rectorClasses = $this->allRectorRulesProvider->provide();

        $sortedClasses = $rectorClasses;
        sort($sortedClasses);

        $this->assertSame($sortedClasses, $rectorClasses);
        $this->assertSame(array_unique($rectorClasses), $rectorClasses);
    }
}
