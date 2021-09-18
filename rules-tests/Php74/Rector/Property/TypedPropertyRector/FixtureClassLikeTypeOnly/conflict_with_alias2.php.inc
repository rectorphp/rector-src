<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureClassLikeTypeOnly;

use Psr\Container\ContainerInterface;
use Psr\Container\ContainerInterface as PsrContainerInterface;

final class ConflictWithAlias2 implements ContainerInterface
{
    /**
     * @var PsrContainerInterface
     */
    private $anotherClass;

    public function __construct(ContainerInterface $anotherClass)
    {
        $this->anotherClass = $anotherClass;
    }
}

?>
