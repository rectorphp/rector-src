<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Catch_;

use Exception;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\TryCatch;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Throwable;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Catch_\CatchThrowableInsteadOfExceptionRector\CatchThrowableInsteadOfExceptionRectorTest
 */
final class CatchThrowableInsteadOfExceptionRector extends AbstractRector
{
    public function __construct(
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly UseNodesToAddCollector $useNodesToAddCollector
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace last catch Exception with catch Throwable', [
            new CodeSample(
                <<<'CODE_SAMPLE'
try {
    $this->doSomething();
} catch (Exception $exception) {
    // do something
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
try {
    $this->doSomething();
} catch (Throwable $exception) {
    // do something
}
CODE_SAMPLE
                ,
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [TryCatch::class];
    }

    /**
     * @param TryCatch $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof TryCatch) {
            return $node;
        }

        $hasChanged = false;
        foreach ($node->catches as $key => $catch) {
            if ($this->isLastCatchCatchingOnlyException($node, $key, $catch)) {
                $this->replaceCatchExceptionWithThrowable($catch);

                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function isLastCatchCatchingOnlyException(TryCatch $node, int $key, Catch_ $catch): bool
    {

        if ($key !== count($node->catches) - 1) {
            return false;
        }

        return $this->array_find($catch->types, fn (Name $type) => $this->isName($type, Exception::class)) !== null
            && $this->array_find($catch->types, fn (Name $type) => $this->isName($type, Throwable::class)) === null;
    }

    private function addUseImport(string $class): void
    {
        $file = $this->currentFileProvider->getFile();
        if ($file === null) {
            return;
        }

        $fullyQualified = new FullyQualified($class);
        $fullyQualifiedObjectType = new FullyQualifiedObjectType($class);
        if (! $this->useNodesToAddCollector->hasImport($file, $fullyQualified, $fullyQualifiedObjectType)) {
            $this->useNodesToAddCollector->addUseImport($fullyQualifiedObjectType);
        }
    }

    private function replaceCatchExceptionWithThrowable(Catch_ $catch): void
    {
        $replaceKey = $this->array_find_key($catch->types, fn ($type) => $this->isName($type, Exception::class));
        if ($replaceKey !== null) {
            $this->addUseImport(Throwable::class);
            $catch->types[$replaceKey] = new Name(Throwable::class);
        }
    }

    /**
     * @template T
     * @param array<int|string, T> $array
     * @param callable(T): bool $func
     * @return T|null
     */
    private function array_find(array $array, callable $func): mixed
    {
        return array_values(array_filter($array, $func))[0] ?? null;
    }

    /**
     * @template T
     * @param array<int|string, T> $array
     * @param callable(T): bool $func
     */
    private function array_find_key(array $array, callable $func): string|int|null
    {
        return array_key_first(array_filter($array, $func));
    }
}
