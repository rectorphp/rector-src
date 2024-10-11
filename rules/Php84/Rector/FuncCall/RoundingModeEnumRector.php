<?php

declare(strict_types=1);

namespace Rector\Php84\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\UnionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\TypeCombinator;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php84\Rector\FuncCall\RoundingModeEnumRector\RoundingModeEnumRectorTest
 */
final class RoundingModeEnumRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ValueResolver    $valueResolver,
        private readonly StaticTypeMapper $staticTypeMapper
    )
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace rounding mode constant to RoundMode enum in round()', [
            new CodeSample(
                <<<'CODE_SAMPLE'
round(1.5, 0, PHP_ROUND_HALF_UP);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
round(1.5, 0, RoundingMode::HalfAwayFromZero);
CODE_SAMPLE
                ,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node\Expr\FuncCall
    {

        if (!$this->isName($node, 'round')) {
            return null;
        }

        $args = $node->getArgs();

        if (count($args) !== 3) {
            return null;
        }

        $modeArg = $args[2]->value;

        if ($modeArg instanceof ConstFetch) {
            if (isset($modeArg->name->getParts()[0])) {
                $enumCase = match ($modeArg->name->getParts()[0]) {
                    'PHP_ROUND_HALF_UP' => 'HalfAwayFromZero',
                    'PHP_ROUND_HALF_DOWN' => 'HalfTowardsZero',
                    'PHP_ROUND_HALF_EVEN' => 'HalfEven',
                    'PHP_ROUND_HALF_ODD' => 'HalfOdd',
                    default => null,
                };

                if ($enumCase === null) {
                    return null;
                }

                $args[2]->value = new Node\Expr\ClassConstFetch(new Name('RoundingMode'), $enumCase);
            }
        }


        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ROUNDING_MODES;
    }
}
