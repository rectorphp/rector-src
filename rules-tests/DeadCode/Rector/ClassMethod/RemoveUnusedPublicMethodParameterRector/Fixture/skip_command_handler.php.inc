<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SkipCommandHandler
{
    public function __invoke(string $name): void
    {
    }
}
