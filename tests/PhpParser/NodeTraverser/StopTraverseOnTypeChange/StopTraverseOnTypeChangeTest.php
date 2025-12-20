<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange\Class_\RuleChangingClassToTraitRector;
use Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange\Class_\RuleCheckingClassRector;

final class StopTraverseOnTypeChangeTest extends AbstractLazyTestCase
{
    private RectorNodeTraverser $rectorNodeTraverser;

    protected function setUp(): void
    {
        $this->rectorNodeTraverser = $this->make(RectorNodeTraverser::class);

        $this->rectorNodeTraverser->refreshPhpRectors([
            $this->make(RuleChangingClassToTraitRector::class),
            $this->make(RuleCheckingClassRector::class)
        ]);

        $currentFileProvider = $this->make(CurrentFileProvider::class);

        // @todo
        // $currentFileProvider->setFile();
    }

    public function testGetVisitorsForNodeWhenNoVisitorsAvailable(): void
    {
        $class = new Class_('test');

        $changedNodes = $this->rectorNodeTraverser->traverse([$class]);
        $this->assertContainsOnlyInstancesOf(Trait_::class, $changedNodes);
    }
}
