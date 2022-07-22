<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\PhpDoc\TagRemover\ParamTagRemover;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\MixedTypeRectorTest
 */
final class MixedTypeRector extends AbstractRector implements MinPhpVersionInterface
{
    private bool $hasChanged = false;

    public function __construct(private readonly ParamTagRemover $paramTagRemover)
    {
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
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $this->refactorParamTypes($node, $phpDocInfo);

        if (! $this->hasChanged) {
            return null;
        }

        $this->paramTagRemover->removeParamTagsIfUseless($phpDocInfo, $node);
        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::MIXED_TYPE;
    }

    private function refactorParamTypes(
        ClassMethod | Function_ | Closure | ArrowFunction $functionLike,
        PhpDocInfo $phpDocInfo
    ): void {
        foreach ($functionLike->params as $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            $paramName = $this->getName($param->var);
            $paramType = $phpDocInfo->getParamType($paramName);

            if (! $paramType instanceof MixedType) {
                continue;
            }

            $this->hasChanged = true;
            $param->type = new Name('mixed');
        }
    }
}
