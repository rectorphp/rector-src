<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\Expression;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/deprecations_php_8_5#deprecate_the_http_response_header_predefined_variable
 * @see \Rector\Tests\Php85\Rector\Expression\ReplaceHttpResponseHeaderRector\ReplaceHttpResponseHeaderRectorTest
 */
final class ReplaceHttpResponseHeaderRector extends AbstractRector implements MinPhpVersionInterface
{
     public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SERVER_VAR;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Assign http_get_last_response_headers() to $http_response_header if used without assignment', [
            new CodeSample(
                <<<'CODE_SAMPLE'

echo $http_response_header;

CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'

$http_response_header = http_get_last_response_headers();

echo $http_response_header;

CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Node $node
     * @return array<int, Node>
     */
    public function refactor(Node $node): ?Array
    {  
        $variables = $this->betterNodeFinder->findInstanceOf($node, Variable::class);

        foreach ($variables as $var) {
            if( $var->getAttribute(AttributeKey::IS_BEING_ASSIGNED)){
                return null;
            }
            if ($this->getName($var) === 'http_response_header') {

                $assign = new Expression(
                    new Assign(
                        new Variable('http_response_header'),
                        new FuncCall(new Name('http_get_last_response_headers'))
                    )
                );
                return [$assign, $node];
            }
        }

        return null; 
    }
}
