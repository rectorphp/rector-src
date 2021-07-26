<?php

declare(strict_types=1);

namespace Rector\Core\NodeAnalyzer;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class ClassAnalyzer
{
    /**
     * @var string
     * @see https://regex101.com/r/FQH6RT/1
     */
    private const ANONYMOUS_CLASS_REGEX = '#AnonymousClass\w+$#';

    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isAnonymousClass(Node $node): bool
    {
        if (! $node instanceof Class_) {
            return false;
        }

        if ($node->isAnonymous()) {
            return true;
        }

        /** @var string $className */
        $className = $this->nodeNameResolver->getName($node);
        if (! $this->reflectionProvider->hasClass($className)) {
            return true;
        }

        // match PHPStan pattern for anonymous classes
        return (bool) Strings::match($className, self::ANONYMOUS_CLASS_REGEX);
    }

    public function isAnonymousClassOfClassMethod(ClassMethod $classMethod): bool
    {
        $class = $classMethod->getAttribute(AttributeKey::CLASS_NODE);
        if (! $class instanceof Class_) {
            return false;
        }

        return $this->isAnonymousClass($class);
    }
}
