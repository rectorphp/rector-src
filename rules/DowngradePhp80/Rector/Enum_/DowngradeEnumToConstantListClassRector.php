<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\Enum_;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Enum_;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\DowngradePhp80\NodeAnalyzer\EnumAnalyzer;
use Rector\Php81\NodeFactory\ClassFromEnumFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\Enum_\DowngradeEnumToConstantListClassRector\DowngradeEnumToConstantListClassRectorTest
 */
final class DowngradeEnumToConstantListClassRector extends AbstractRector
{
    public function __construct(
        private readonly ClassFromEnumFactory $classFromEnumFactory,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly EnumAnalyzer $enumAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Downgrade enum to constant list class', [
            new CodeSample(
                <<<'CODE_SAMPLE'
enum Direction
{
    case LEFT;

    case RIGHT;
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class Direction
{
    public const LEFT = 'left';

    public const RIGHT = 'right';
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
        return [Enum_::class, ClassMethod::class];
    }

    /**
     * @param Enum_|ClassMethod $node
     */
    public function refactor(Node $node): Class_|ClassMethod|null
    {
        if ($node instanceof Enum_) {
            return $this->classFromEnumFactory->createFromEnum($node);
        }

        $hasChanged = false;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        foreach ($node->params as $param) {
            if (! $param->type instanceof Name) {
                continue;
            }

            // is enum type?
            $typeName = $this->getName($param->type);

            if (! $this->reflectionProvider->hasClass($typeName)) {
                continue;
            }

            $classLikeReflection = $this->reflectionProvider->getClass($typeName);
            if (! $classLikeReflection->isEnum()) {
                continue;
            }

            $param->type = $this->resolveParamType($classLikeReflection);
            $hasChanged = true;

            $this->decorateParamDocType($classLikeReflection, $param, $phpDocInfo);
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function resolveParamType(ClassReflection $classReflection): ?Identifier
    {
        return $this->enumAnalyzer->resolveType($classReflection);
    }

    private function decorateParamDocType(
        ClassReflection $classReflection,
        Param $param,
        PhpDocInfo $phpDocInfo
    ): void {
        $constFetchNode = new ConstFetchNode('\\' . $classReflection->getName(), '*');
        $constTypeNode = new ConstTypeNode($constFetchNode);
        $paramName = '$' . $this->getName($param);

        $paramTagValueNode = new ParamTagValueNode($constTypeNode, false, $paramName, '');

        $phpDocInfo->addTagValueNode($paramTagValueNode);
    }
}
