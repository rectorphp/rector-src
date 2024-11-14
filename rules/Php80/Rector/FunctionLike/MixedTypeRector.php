<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\DeadCode\PhpDoc\TagRemover\ParamTagRemover;
use Rector\FamilyTree\NodeAnalyzer\ClassChildAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @deprecated Instead blindly filling `mixed` type, use "typeDeclarations" set with explicit types.
 */
final class MixedTypeRector extends AbstractRector implements MinPhpVersionInterface, DeprecatedInterface
{
    private bool $hasChanged = false;

    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ClassChildAnalyzer $classChildAnalyzer,
        private readonly ParamTagRemover $paramTagRemover,
        private readonly PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change mixed docs type to mixed typed',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param mixed $param
     */
    public function run($param)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run(mixed $param)
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class, Closure::class, ArrowFunction::class];
    }

    /**
     * @param ClassMethod|Function_|Closure|ArrowFunction $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ClassMethod && $this->shouldSkipClassMethod($node)) {
            return null;
        }

        $this->hasChanged = false;

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $this->refactorParamTypes($node, $phpDocInfo);
        $hasChanged = $this->paramTagRemover->removeParamTagsIfUseless($phpDocInfo, $node, new MixedType());

        if ($this->hasChanged || $hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::MIXED_TYPE;
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($classMethod);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);
        return $this->classChildAnalyzer->hasParentClassMethod($classReflection, $methodName);
    }

    private function refactorParamTypes(
        ClassMethod | Function_ | Closure | ArrowFunction $functionLike,
        PhpDocInfo $phpDocInfo
    ): void {
        foreach ($functionLike->params as $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            $paramName = (string) $this->getName($param->var);
            $paramTagValue = $phpDocInfo->getParamTagValueByName($paramName);

            if (! $paramTagValue instanceof ParamTagValueNode) {
                continue;
            }

            $paramType = $phpDocInfo->getParamType($paramName);

            if (! $paramType instanceof MixedType) {
                continue;
            }

            $this->hasChanged = true;
            $param->type = new Identifier('mixed');
            if ($param->flags !== 0) {
                $param->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            }
        }
    }
}
