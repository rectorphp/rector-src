<?php

declare(strict_types=1);

namespace Rector\DowngradePhp80\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\UnionType;
use Rector\BetterPhpDocParser\PhpDocParser\PhpDocFromTypeDeclarationDecorator;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector\DowngradeUnionTypeDeclarationRectorTest
 */
final class DowngradeUnionTypeDeclarationRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocFromTypeDeclarationDecorator $phpDocFromTypeDeclarationDecorator
    ) {
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Function_::class, ClassMethod::class, Closure::class, ArrowFunction::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the union type params and returns, add @param/@return tags instead',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function echoInput(string|int $input): int|bool
    {
        echo $input;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @param string|int $input
     * @return int|bool
     */
    public function echoInput($input)
    {
        echo $input;
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @param ClassMethod|Closure|Function_|ArrowFunction $node
     */
    public function refactor(Node $node): ?Node
    {
        $paramDecorated = false;
        foreach ($node->getParams() as $param) {
            if (! $param->type instanceof UnionType) {
                continue;
            }

            $this->phpDocFromTypeDeclarationDecorator->decorateParam($param, $node, [\PHPStan\Type\UnionType::class]);
            $paramDecorated = true;
        }

        if (! $node->returnType instanceof UnionType) {
            if ($paramDecorated) {
                return $node;
            }

            return null;
        }

        $this->phpDocFromTypeDeclarationDecorator->decorate($node);

        return $node;
    }
}
