<?php

declare(strict_types=1);

namespace Rector\Php55\Rector\String_;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\NodeTraverser;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @changelog https://wiki.php.net/rfc/class_name_scalars https://github.com/symfony/symfony/blob/2.8/UPGRADE-2.8.md#form
 *
 * @see \Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\StringClassNameToClassConstantRectorTest
 */
final class StringClassNameToClassConstantRector extends AbstractRector implements MinPhpVersionInterface, ConfigurableRectorInterface
{
    /**
     * @var string
     */
    private const IS_UNDER_CLASS_CONST = 'is_under_class_const';

    /**
     * @var string[]
     */
    private array $classesToSkip = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace string class names by <class>::class constant', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class AnotherClass
{
}

class SomeClass
{
    public function run()
    {
        return 'AnotherClass';
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class AnotherClass
{
}

class SomeClass
{
    public function run()
    {
        return \AnotherClass::class;
    }
}
CODE_SAMPLE
                ,
                ['ClassName', 'AnotherClassName'],
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [String_::class, FuncCall::class, ClassConst::class];
    }

    /**
     * @param String_|FuncCall|ClassConst $node
     */
    public function refactor(Node $node): Concat|ClassConstFetch|null|int
    {
        // allow class strings to be part of class const arrays, as probably on purpose
        if ($node instanceof ClassConst) {
            $this->traverseNodesWithCallable($node->consts, static function (Node $subNode) {
                if ($subNode instanceof String_) {
                    $subNode->setAttribute(self::IS_UNDER_CLASS_CONST, true);
                }

                return null;
            });

            return null;
        }

        // keep allowed string as condition
        if ($node instanceof FuncCall) {
            if ($this->isName($node, 'is_a')) {
                return NodeTraverser::DONT_TRAVERSE_CHILDREN;
            }

            return null;
        }

        if ($node->getAttribute(self::IS_UNDER_CLASS_CONST) === true) {
            return null;
        }

        $classLikeName = $node->value;

        // remove leading slash
        $classLikeName = ltrim($classLikeName, '\\');
        if ($classLikeName === '') {
            return null;
        }

        if ($this->shouldSkip($classLikeName)) {
            return null;
        }

        $fullyQualified = new FullyQualified($classLikeName);

        if ($classLikeName !== $node->value) {
            $preSlashCount = strlen($node->value) - strlen($classLikeName);
            $preSlash = str_repeat('\\', $preSlashCount);
            $string = new String_($preSlash);

            return new Concat($string, new ClassConstFetch($fullyQualified, 'class'));
        }

        return new ClassConstFetch($fullyQualified, 'class');
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString($configuration);

        $this->classesToSkip = $configuration;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::CLASSNAME_CONSTANT;
    }

    private function shouldSkip(string $classLikeName): bool
    {
        // skip short class names, mostly invalid use of strings
        if (! str_contains($classLikeName, '\\')) {
            return true;
        }

        // possibly string
        if (ctype_lower($classLikeName[0])) {
            return true;
        }

        if (! $this->reflectionProvider->hasClass($classLikeName)) {
            return true;
        }

        foreach ($this->classesToSkip as $classToSkip) {
            if (str_contains($classToSkip, '*')) {
                if (fnmatch($classToSkip, $classLikeName, FNM_NOESCAPE)) {
                    return true;
                }

                continue;
            }

            if ($this->nodeNameResolver->isStringName($classLikeName, $classToSkip)) {
                return true;
            }
        }

        return false;
    }
}
