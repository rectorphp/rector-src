<?php

declare(strict_types=1);

namespace Rector\MockeryToProphecy\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\MockeryToProphecy\Collector\MockVariableCollector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPUnit\NodeAnalyzer\TestsNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\MockeryToProphecy\Rector\ClassMethod\MockeryToProphecyRector\MockeryToProphecyRectorTest
 */
final class MockeryCreateMockToProphizeRector extends AbstractRector
{
    /**
     * @var array<string, class-string>
     */
    private array $mockVariableTypesByNames = [];

    public function __construct(
        private MockVariableCollector $mockVariableCollector,
        private TestsNodeAnalyzer $testsNodeAnalyzer
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->testsNodeAnalyzer->isInTestClass($node)) {
            return null;
        }

        $this->replaceMockCreationsAndCollectVariableNames($node);
        $this->revealMockArguments($node);

        return $node;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes mockery mock creation to Prophesize',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$mock = \Mockery::mock('MyClass');
$service = new Service();
$service->injectDependency($mock);
CODE_SAMPLE
,
                    <<<'CODE_SAMPLE'
 $mock = $this->prophesize('MyClass');

$service = new Service();
$service->injectDependency($mock->reveal());
CODE_SAMPLE
                ),
            ]
        );
    }

    private function replaceMockCreationsAndCollectVariableNames(ClassMethod $classMethod): void
    {
        if ($classMethod->stmts === null) {
            return;
        }

        $this->traverseNodesWithCallable($classMethod->stmts, function (Node $node): ?MethodCall {
            if (! $node instanceof StaticCall) {
                return null;
            }

            $callerType = $this->nodeTypeResolver->getType($node->class);
            if (! $callerType->isSuperTypeOf(new ObjectType('Mockery'))->yes()) {
                return null;
            }

            if (! $this->isName($node->name, 'mock')) {
                return null;
            }

            $collectedVariableTypesByNames = $this->mockVariableCollector->collectMockVariableName($node);

            $this->mockVariableTypesByNames = array_merge(
                $this->mockVariableTypesByNames,
                $collectedVariableTypesByNames
            );

            $parentNode = $node->getAttribute(AttributeKey::PARENT_NODE);
            if ($parentNode instanceof Arg) {
                $prophesizeMethodCall = $this->createProphesizeMethodCall($node);
                return $this->nodeFactory->createMethodCall($prophesizeMethodCall, 'reveal');
            }

            return $this->createProphesizeMethodCall($node);
        });
    }

    private function revealMockArguments(ClassMethod $classMethod): void
    {
        if ($classMethod->stmts === null) {
            return;
        }

        $this->traverseNodesWithCallable($classMethod->stmts, function (Node $node): ?Arg {
            if (! $node instanceof Arg) {
                return null;
            }

            if (! $node->value instanceof Variable) {
                return null;
            }

            /** @var string $variableName */
            $variableName = $this->getName($node->value);

            if (! isset($this->mockVariableTypesByNames[$variableName])) {
                return null;
            }

            $methodCall = $this->nodeFactory->createMethodCall($node->value, 'reveal');
            $node->value = $methodCall;
            return $node;
        });
    }

    private function createProphesizeMethodCall(StaticCall $staticCall): MethodCall
    {
        return $this->nodeFactory->createLocalMethodCall('prophesize', [$staticCall->args[0]]);
    }
}
