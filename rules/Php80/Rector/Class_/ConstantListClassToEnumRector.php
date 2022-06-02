<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\Php\PhpParameterReflection;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Php80\NodeAnalyzer\EnumConstListClassDetector;
use Rector\Php81\NodeFactory\EnumFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php80\Rector\Class_\ConstantListClassToEnumRector\ConstantListClassToEnumRectorTest
 */
final class ConstantListClassToEnumRector extends AbstractRector
{
    public function __construct(
        private readonly EnumConstListClassDetector $enumConstListClassDetector,
        private readonly EnumFactory $enumFactory,
        private readonly ReflectionResolver $reflectionResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Upgrade constant list classes to full blown enum', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class Direction
{
    public const LEFT = 'left';

    public const RIGHT = 'right';
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
enum Direction
{
    case LEFT;

    case RIGHT;
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class, ClassMethod::class];
    }

    /**
     * @param Class_|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Class_) {
            if (! $this->enumConstListClassDetector->detect($node)) {
                return null;
            }

            return $this->enumFactory->createFromClass($node);
        }

        return $this->refactorClassMethod($node);
    }

    private function refactorClassMethod(ClassMethod $classMethod): ?ClassMethod
    {
        if ($classMethod->params === []) {
            return null;
        }

        // enum param types doc requires a docblock
        $phpDocInfo = $this->phpDocInfoFactory->createFromNode($classMethod);
        if (! $phpDocInfo instanceof PhpDocInfo) {
            return null;
        }

        $methodReflection = $this->reflectionResolver->resolveMethodReflectionFromClassMethod($classMethod);

        $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());
        foreach ($parametersAcceptor->getParameters() as $parameterReflection) {
            if (! $parameterReflection instanceof PhpParameterReflection) {
                continue;
            }

            // should be union, that is how PHPStan resolves it
            if (! $parameterReflection->getType() instanceof UnionType) {
                continue;
            }

            $paramTagValueNode = $phpDocInfo->getParamTagValueByName($parameterReflection->getName());
            if ($paramTagValueNode->type instanceof ConstTypeNode) {
                $constTypeNode = $paramTagValueNode->type;
                if ($constTypeNode->constExpr instanceof ConstFetchNode) {
                    $constExpr = $constTypeNode->constExpr;
                    dump($constExpr->getAttribute(PhpDocAttributeKey::RESOLVED_CLASS));
                    dump('___');
                    die;
                }
            }
        }

        return null;
    }
}
