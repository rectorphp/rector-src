<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source\SomeParentCommand;

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
