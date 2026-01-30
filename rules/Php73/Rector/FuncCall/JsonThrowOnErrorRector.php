<?php

declare(strict_types=1);

namespace Rector\Php73\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\Int_;
use Rector\PhpParser\Enum\NodeGroup;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php73\Rector\FuncCall\JsonThrowOnErrorRector\JsonThrowOnErrorRectorTest
 */
final class JsonThrowOnErrorRector extends AbstractRector implements MinPhpVersionInterface
{
    private bool $hasChanged = false;
    private const FLAGS = ['JSON_THROW_ON_ERROR'];

    public function __construct(
        private readonly ValueResolver $valueResolver,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Adds JSON_THROW_ON_ERROR to json_encode() and json_decode() to throw JsonException on error',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
json_encode($content);
json_decode($json);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
json_encode($content, JSON_THROW_ON_ERROR);
json_decode($json, null, 512, JSON_THROW_ON_ERROR);
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
        return NodeGroup::STMTS_AWARE;
    }

    /**
     * @param StmtsAware $node
     */
    public function refactor(Node $node): ?Node
    {
        // if found, skip it :)
        $hasJsonErrorFuncCall = (bool) $this->betterNodeFinder->findFirst(
            $node,
            fn (Node $node): bool => $this->isNames($node, ['json_last_error', 'json_last_error_msg'])
        );

        if ($hasJsonErrorFuncCall) {
            return null;
        }

        $this->hasChanged = false;

        $this->traverseNodesWithCallable($node, function (Node $currentNode): ?FuncCall {
            if (! $currentNode instanceof FuncCall) {
                return null;
            }

            if ($this->shouldSkipFuncCall($currentNode)) {
                return null;
            }

            if ($this->isName($currentNode, 'json_encode')) {
                return $this->processJsonEncode($currentNode);
            }

            if ($this->isName($currentNode, 'json_decode')) {
                return $this->processJsonDecode($currentNode);
            }

            return null;
        });

        if ($this->hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::JSON_EXCEPTION;
    }

    private function shouldSkipFuncCall(FuncCall $funcCall): bool
    {
        if ($funcCall->isFirstClassCallable()) {
            return true;
        }

        if ($funcCall->args === []) {
            return true;
        }

        foreach ($funcCall->args as $arg) {
            if (! $arg instanceof Arg) {
                continue;
            }

            if ($arg->name instanceof Identifier) {
                return true;
            }
        }

        return $this->isFirstValueStringOrArray($funcCall);
    }

    private function processJsonEncode(FuncCall $funcCall): ?FuncCall
    {
        $flags = [];
        if (isset($funcCall->args[1])) {
            $flags = $this->getFlags($funcCall->args[1]);
        }
        if (!is_null($newArg = $this->getArgWithFlags($flags))) {
            $this->hasChanged = true;
            $funcCall->args[1] = $newArg;
        }
        return $funcCall;
    }

    private function processJsonDecode(FuncCall $funcCall): ?FuncCall
    {
        $flags = [];
        if (isset($funcCall->args[3])) {
            $flags = $this->getFlags($funcCall->args[3]);
        }

        // set default to inter-args
        if (! isset($funcCall->args[1])) {
            $funcCall->args[1] = new Arg($this->nodeFactory->createNull());
        }

        if (! isset($funcCall->args[2])) {
            $funcCall->args[2] = new Arg(new Int_(512));
        }

        if (!is_null($newArg = $this->getArgWithFlags($flags))) {
            $this->hasChanged = true;
            $funcCall->args[3] = $newArg;
        }
        return $funcCall;
    }

    private function createConstFetch(string $name): ConstFetch
    {
        return new ConstFetch(new Name($name));
    }

    private function isFirstValueStringOrArray(FuncCall $funcCall): bool
    {
        if (! isset($funcCall->getArgs()[0])) {
            return false;
        }

        $firstArg = $funcCall->getArgs()[0];

        $value = $this->valueResolver->getValue($firstArg->value);
        if (is_string($value)) {
            return true;
        }

        return is_array($value);
    }

    private function getFlags(Arg|Node\Expr\BinaryOp\BitwiseOr|ConstFetch $arg, array $result = []): array
    {
        if ($arg instanceof ConstFetch) {
            $constFetch = $arg;
        } else {
            if ($arg instanceof Arg) {
                $array = $arg->value->jsonSerialize();
            } else {
                $array = $arg->jsonSerialize();
            }
            if ($arg->value instanceof ConstFetch) { // single flag
                $constFetch = $arg->value;
            } else { // multiple flag
                $result = $this->getFlags($array['left'], $result);
                $constFetch = $array['right'];
            }
        }
        if (!is_null($constFetch)) {
            $result[] = $constFetch->jsonSerialize()['name']->getFirst();
        }
        return $result;
    }

    private function getArgWithFlags(array $flags): Arg|null
    {
        $oldNbFlags = count($flags);
        $flags = array_values(array_unique([...$flags, ...self::FLAGS]));
        $newNbFlags = count($flags);
        if ($oldNbFlags === $newNbFlags) {
            return null;
        }
        if ($newNbFlags === 1) {
            return new Arg($this->createConstFetch($flags[0]));
        }
        $constFetchs = [];
        foreach ($flags as $flag) {
            $constFetchs[] = $this->createConstFetch($flag);
        }
        $result = null;
        foreach ($constFetchs as $i => $constFetch) {
            if ($i === 1) {
                continue;
            }
            if (is_null($result)) {
                $result = new Node\Expr\BinaryOp\BitwiseOr(
                    $constFetch,
                    $constFetchs[$i + 1],
                );
            } else {
                $result = new Node\Expr\BinaryOp\BitwiseOr(
                    $result,
                    $constFetch
                );
            }
        }
        return new Arg($result);
    }
}
