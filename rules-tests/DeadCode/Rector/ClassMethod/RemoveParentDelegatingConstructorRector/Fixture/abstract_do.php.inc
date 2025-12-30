<?php

declare(strict_types=1);

namespace App;

use App\AbstractFilterExtension;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

abstract class AbstractDo
{
    public function __construct(private readonly ContainerInterface $filterLocator)
    {
    }

    abstract protected function doSomething(): void;
}

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

final class DoSomething extends AbstractDo
{
    public function __construct(
        #[Autowire(service: 'service_container')]
        ContainerInterface $filterLocator,
    ) {
        parent::__construct($filterLocator);
    }

    protected function doSomething(): void
    {
    }

}
?>
