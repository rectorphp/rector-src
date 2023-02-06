<?php


namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[Route('/foo')]
final class FooController
{
    public function __construct(
        private readonly Responder $responder,
        private readonly RepositoryInterface $repository,
    ) {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[Route('/foo')]
final readonly class FooController
{
    public function __construct(
        private Responder $responder,
        private RepositoryInterface $repository,
    ) {
    }
}

?>
