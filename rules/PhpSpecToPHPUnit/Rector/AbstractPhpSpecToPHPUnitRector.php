<?php

declare(strict_types=1);

namespace Rector\PhpSpecToPHPUnit\Rector;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://gnugat.github.io/2015/09/23/phpunit-with-phpspec.html
 * @changelog http://www.phpspec.net/en/stable/cookbook/construction.html
 */
abstract class AbstractPhpSpecToPHPUnitRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Migrate PhpSpec behavior to PHPUnit test', [
            new CodeSample(
                <<<'CODE_SAMPLE'
namespace spec\SomeNamespaceForThisTest;

use PhpSpec\ObjectBehavior;

class OrderSpec extends ObjectBehavior
{
    public function let(OrderFactory $factory, ShippingMethod $shippingMethod): void
    {
        $factory->createShippingMethodFor(Argument::any())->shouldBeCalled()->willReturn($shippingMethod);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
namespace spec\SomeNamespaceForThisTest;

class OrderSpec extends ObjectBehavior
{
    /**
     * @var \SomeNamespaceForThisTest\Order
     */
    private $order;
    protected function setUp()
    {
        /** @var OrderFactory|\PHPUnit\Framework\MockObject\MockObject $factory */
        $factory = $this->createMock(OrderFactory::class);

        /** @var ShippingMethod|\PHPUnit\Framework\MockObject\MockObject $shippingMethod */
        $shippingMethod = $this->createMock(ShippingMethod::class);

        $factory->expects($this->once())->method('createShippingMethodFor')->willReturn($shippingMethod);
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    protected function isInPhpSpecBehavior(Node $node): bool
    {
        if ($node instanceof ClassLike) {
            return $this->isObjectType($node, new ObjectType('PhpSpec\ObjectBehavior'));
        }

        $classLike = $this->betterNodeFinder->findParentType($node, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return false;
        }

        return $this->isInPhpSpecBehavior($classLike);
    }
}
