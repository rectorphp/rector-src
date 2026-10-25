<?php

namespace Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\Class_\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

final class SkipAnnotationDocblock extends SomeParentCommand
{
    /**
     * @Route("/first", methods={"GET"})
     */
    public function anotherMethod(): void
    {
        parent::anotherMethod();
    }
}
