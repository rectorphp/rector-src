<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use Rector\Php\PhpVersionProvider;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
<<<<<<< HEAD
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
=======
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
>>>>>>> 971356b355 (add scalar types condition)
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\KnownMagicClassMethodTypeRectorTest
 *
 * @see https://www.php.net/manual/en/language.oop5.overloading.php#object.call
 */
final class KnownMagicClassMethodTypeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
<<<<<<< HEAD
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard
    ){
    }
=======
        private readonly PhpVersionProvider $phpVersionProvider
    ) {

    }

>>>>>>> 3a091179f1 (add call static support, set and get)
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add known magic methods parameter and return type declarations',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function __call($method, $args)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function __call(string $method, array $args)
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if (! $classMethod->isMagic()) {
                continue;
            }

<<<<<<< HEAD
            if (! $this->isName($classMethod, MethodName::CALL)) {
                continue;
            }
=======
            if ($this->isNames($classMethod, [MethodName::CALL, MethodName::CALL_STATIC])) {
                $firstParam = $classMethod->getParams()[0];
                if (! $firstParam->type instanceof Node) {
                    $firstParam->type = new Identifier('string');
                    $hasChanged = true;
                }
>>>>>>> 3a091179f1 (add call static support, set and get)

            if ($this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($classMethod)) {
                return null;
            }

            $firstParam = $classMethod->getParams()[0];
            if (! $firstParam->type instanceof Node) {
                $firstParam->type = new Identifier('string');
                $hasChanged = true;
            }

            $secondParam = $classMethod->getParams()[1];
            if (! $secondParam->type instanceof Node) {
                $secondParam->type = new Name('array');
                $hasChanged = true;
            }

            if ($this->isName($classMethod, MethodName::__GET)) {
                $firstParam = $classMethod->getParams()[0];
                if (! $firstParam->type instanceof Node) {
                    $firstParam->type = new Identifier('string');
                    $hasChanged = true;
                }
            }

            if ($this->isName($classMethod, MethodName::__SET)) {
                $firstParam = $classMethod->getParams()[0];
                if (! $firstParam->type instanceof Node) {
                    $firstParam->type = new Identifier('string');
                    $hasChanged = true;
                }

                if ($this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::MIXED_TYPE)) {
                    $secondParam = $classMethod->getParams()[1];
                    if (! $secondParam->type instanceof Node) {
                        $secondParam->type = new Identifier('mixed');
                        $hasChanged = true;
                    }
                }
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::SCALAR_TYPES;
    }
}
