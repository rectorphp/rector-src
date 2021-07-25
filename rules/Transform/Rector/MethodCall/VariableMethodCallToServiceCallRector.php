<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Naming\Naming\PropertyNaming;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\PropertyToAddCollector;
use Rector\PostRector\ValueObject\PropertyMetadata;
use Rector\Transform\ValueObject\VariableMethodCallToServiceCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Transform\Rector\MethodCall\VariableMethodCallToServiceCallRector\VariableMethodCallToServiceCallRectorTest
 */
final class VariableMethodCallToServiceCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const VARIABLE_METHOD_CALLS_TO_SERVICE_CALLS = 'variable_method_calls_to_service_calls';

    /**
     * @var VariableMethodCallToServiceCall[]
     */
    private array $variableMethodCallsToServiceCalls = [];

    public function __construct(
        private PropertyNaming $propertyNaming,
        private PropertyToAddCollector $propertyToAddCollector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace variable method call to a service one', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
use PhpParser\Node;

class SomeClass
{
    public function run(Node $node)
    {
        $phpDocInfo = $node->getAttribute('php_doc_info');
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use PhpParser\Node;

class SomeClass
{
    public function __construct(PhpDocInfoFactory $phpDocInfoFactory)
    {
        $this->phpDocInfoFactory = $phpDocInfoFactory;
    }
    public function run(Node $node)
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
    }
}
CODE_SAMPLE
                ,
                [
                    self::VARIABLE_METHOD_CALLS_TO_SERVICE_CALLS => [
                        new VariableMethodCallToServiceCall(
                            'PhpParser\Node',
                            'getAttribute',
                            'php_doc_info',
                            'Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory',
                            'createFromNodeOrEmpty'
                        ),
                    ],
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->variableMethodCallsToServiceCalls as $variableMethodCallToServiceCall) {
            if (! $node->var instanceof Variable) {
                continue;
            }

            if (! $this->isObjectType($node->var, $variableMethodCallToServiceCall->getVariableObjectType())) {
                continue;
            }

            if (! $this->isName($node->name, $variableMethodCallToServiceCall->getMethodName())) {
                continue;
            }

            $firstArgValue = $node->args[0]->value;
            if (! $this->valueResolver->isValue(
                $firstArgValue,
                $variableMethodCallToServiceCall->getArgumentValue()
            )) {
                continue;
            }

            $classLike = $node->getAttribute(AttributeKey::CLASS_NODE);
            if (! $classLike instanceof Class_) {
                continue;
            }

            $serviceObjectType = new ObjectType($variableMethodCallToServiceCall->getServiceType());

            $propertyName = $this->propertyNaming->fqnToVariableName($serviceObjectType);
            $propertyMetadata = new PropertyMetadata($propertyName, $serviceObjectType, Class_::MODIFIER_PRIVATE);
            $this->propertyToAddCollector->addPropertyToClass($classLike, $propertyMetadata);

            return $this->createServiceMethodCall(
                $serviceObjectType,
                $variableMethodCallToServiceCall->getServiceMethodName(),
                $node
            );
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->variableMethodCallsToServiceCalls = $configuration[self::VARIABLE_METHOD_CALLS_TO_SERVICE_CALLS] ?? [];
    }

    private function createServiceMethodCall(ObjectType $objectType, string $methodName, MethodCall $node): MethodCall
    {
        $propertyName = $this->propertyNaming->fqnToVariableName($objectType);
        $propertyFetch = new PropertyFetch(new Variable('this'), $propertyName);
        $methodCall = new MethodCall($propertyFetch, $methodName);
        $methodCall->args[] = new Arg($node->var);

        return $methodCall;
    }
}
