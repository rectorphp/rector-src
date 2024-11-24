<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use Rector\Exception\ShouldNotHappenException;
use Rector\Skipper\Skipper\Custom\CustomSkipperInterface;
use Rector\Skipper\Skipper\Custom\ReflectionClassSkipperInterface;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use ReflectionClass;
use Throwable;

final readonly class CustomSkipper implements FileNodeSkipperInterface
{
    public function __construct(
        private CustomSkipperInterface $customSkipper,
    ) {
    }

    public static function supports(mixed $customSkipper): bool
    {
        return $customSkipper instanceof ReflectionClassSkipperInterface;
    }

    public function shouldSkip(string $fileName, ?Node $node): bool
    {
        if ($this->customSkipper instanceof ReflectionClassSkipperInterface && $node instanceof Class_) {
            $reflection = $this->getReflectionClass($node);
            //If the reflection is missing, the class has probably broken syntax and we should wait with this
            // rule until the syntax is healed:
            return $reflection instanceof ReflectionClass ? $this->customSkipper->skip($reflection) : true;
        }

        return false;
    }

    private function getReflectionClass(Class_ $class): ?ReflectionClass
    {
        $fqn = $class->namespacedName->name ?? '';
        if ($fqn !== '') {
            try {
                return new ReflectionClass($fqn);
            } catch (Throwable $throwable) {
                // class doesn't exist or might have broken syntax
                if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
                    throw $throwable;
                }
            }
        }

        if ($class->name instanceof Identifier) {
            throw new ShouldNotHappenException('NameResolver must be configured as node visitor.');
        }

        return null;
    }
}
