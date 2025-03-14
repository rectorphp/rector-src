<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\FuncCall\AddEscapeArgumentRector\AddEscapeArgumentRectorTest
 */
final class AddEscapeArgumentRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add escape argument on CSV function calls', [
            new CodeSample(
                <<<'CODE_SAMPLE'
str_getcsv($string, separator: ',', enclosure: '"');
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
str_getcsv($string, separator: ',', enclosure: '"', escape: '\\');
CODE_SAMPLE
                ,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class, MethodCall::class];
    }

    /**
     * @param FuncCall|MethodCall $node
     */
    public function refactor(Node $node): null|FuncCall|MethodCall
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        if ($node instanceof FuncCall) {
            if (! $this->isNames($node, ['fputcsv', 'fgetcsv', 'str_getcsv'])) {
                return null;
            }

            // already defined in named arg
            foreach ($node->getArgs() as $arg) {
                if ($arg->name instanceof Identifier && $arg->name->toString() === 'escape') {
                    return null;
                }
            }

            if ($this->isNames($node, ['fgetcsv', 'fputcsv'])) {
                $numberArg = 4;
            }  else {
                $numberArg = 4;
            }

            $fourthArg = $node->getArgs()[$numberArg] ?? null;

            if ($fourthArg instanceof Arg) {
                return null;
            }

            $node->args[$numberArg] = new Arg(new String_("\\"));
            return $node;
        }

        //  check on method call here ...

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::REQUIRED_ESCAPE_PARAMETER;
    }
}
