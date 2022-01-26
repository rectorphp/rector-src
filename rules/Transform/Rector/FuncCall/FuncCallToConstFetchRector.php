<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\FuncCall\FuncCallToConstFetchRector\FunctionCallToConstantRectorTest
 */
final class FuncCallToConstFetchRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const FUNCTIONS_TO_CONSTANTS = 'functions_to_constants';

    /**
     * @var string[]
     */
    private array $functionsToConstants = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Changes use of function calls to use constants', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $value = php_sapi_name();
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $value = PHP_SAPI;
    }
}
CODE_SAMPLE
                ,
                [
                    'php_sapi_name' => 'PHP_SAPI',
                ]
            ),

        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $functionName = $this->getName($node);
        if (! is_string($functionName)) {
            return null;
        }

        if (! array_key_exists($functionName, $this->functionsToConstants)) {
            return null;
        }

        return new ConstFetch(new Name($this->functionsToConstants[$functionName]));
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $functionsToConstants = $configuration[self::FUNCTIONS_TO_CONSTANTS] ?? $configuration;
        Assert::isArray($functionsToConstants);
        Assert::allString($functionsToConstants);
        Assert::allString(array_keys($functionsToConstants));

        /** @var array<string, string> $functionsToConstants */
        $this->functionsToConstants = $functionsToConstants;
    }
}
