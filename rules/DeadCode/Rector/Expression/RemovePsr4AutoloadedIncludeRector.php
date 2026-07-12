<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Expression;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Scalar\MagicConst\Dir;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitor;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\FileSystem\JsonFileSystem;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Parser\RectorParser;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Expression\RemovePsr4AutoloadedIncludeRector\RemovePsr4AutoloadedIncludeRectorTest
 */
final class RemovePsr4AutoloadedIncludeRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @api
     */
    public const string COMPOSER_JSON_PATH = 'composer_json_path';

    private ?string $composerJsonPath = null;

    /**
     * @var array<array{string, string}>|null list of [namespace prefix, absolute directory] pairs
     */
    private ?array $psr4Prefixes = null;

    public function __construct(
        private readonly RectorParser $rectorParser,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @param array<string, mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        $composerJsonPath = $configuration[self::COMPOSER_JSON_PATH] ?? null;
        $this->composerJsonPath = is_string($composerJsonPath) ? $composerJsonPath : null;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove include/require of a file that is already autoloaded via composer.json PSR-4',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
require __DIR__ . '/src/SomeClass.php';

$someClass = new App\SomeClass();
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$someClass = new App\SomeClass();
CODE_SAMPLE
                    ,
                    [
                        self::COMPOSER_JSON_PATH => 'composer.json',
                    ]
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): int|null
    {
        if (! $node->expr instanceof Include_) {
            return null;
        }

        $includedFilePath = $this->resolveIncludedFilePath($node->expr);
        if ($includedFilePath === null) {
            return null;
        }

        if (! $this->isAutoloadedViaPsr4($includedFilePath)) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function resolveIncludedFilePath(Include_ $include): ?string
    {
        $currentDirectory = dirname($this->file->getFilePath());

        // __DIR__ . '/relative/path.php'
        if ($include->expr instanceof Concat && $include->expr->left instanceof Dir && $include->expr->right instanceof String_) {
            return $this->realPath($currentDirectory . $include->expr->right->value);
        }

        if (! $include->expr instanceof String_) {
            return null;
        }

        $rawPath = $include->expr->value;

        // absolute path
        if (str_starts_with($rawPath, '/')) {
            return $this->realPath($rawPath);
        }

        return $this->realPath($currentDirectory . '/' . $rawPath);
    }

    private function realPath(string $path): ?string
    {
        if (! is_file($path)) {
            return null;
        }

        return realpath($path);
    }

    private function isAutoloadedViaPsr4(string $includedFilePath): bool
    {
        $declaredClassName = $this->resolveSingleDeclaredClassName($includedFilePath);
        if ($declaredClassName === null) {
            return false;
        }

        foreach ($this->providePsr4Prefixes() as [$namespacePrefix, $directory]) {
            if (! str_starts_with($declaredClassName, $namespacePrefix)) {
                continue;
            }

            $relativeClassName = substr($declaredClassName, strlen($namespacePrefix));
            $expectedFilePath = $this->realPath($directory . '/' . str_replace('\\', '/', $relativeClassName) . '.php');

            if ($expectedFilePath === $includedFilePath) {
                return true;
            }
        }

        return false;
    }

    private function resolveSingleDeclaredClassName(string $filePath): ?string
    {
        $stmts = $this->rectorParser->parseFile($filePath);

        // the autoloader replays only type declarations, so top-level side effects, functions or constants keep the require load-bearing
        if (! $this->containsOnlyTypeDeclarations($stmts)) {
            return null;
        }

        $classLikes = $this->betterNodeFinder->findInstanceOf($stmts, ClassLike::class);

        // require must define exactly one class, or removing it would drop the other definitions
        if (count($classLikes) !== 1) {
            return null;
        }

        // RichParser resolves names, so this is already the fully-qualified class name
        return $this->getName($classLikes[0]);
    }

    /**
     * @param Stmt[] $stmts
     */
    private function containsOnlyTypeDeclarations(array $stmts): bool
    {
        foreach ($stmts as $stmt) {
            if ($stmt instanceof ClassLike || $stmt instanceof Use_ || $stmt instanceof GroupUse || $stmt instanceof Nop) {
                continue;
            }

            // declare(strict_types=1); is fine, but the block form declare(...) { ... } executes its body
            if ($stmt instanceof Declare_ && $stmt->stmts === null) {
                continue;
            }

            if ($stmt instanceof Namespace_) {
                if (! $this->containsOnlyTypeDeclarations($stmt->stmts)) {
                    return false;
                }

                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * @return array<array{string, string}> list of [namespace prefix, absolute directory] pairs
     */
    private function providePsr4Prefixes(): array
    {
        if ($this->psr4Prefixes !== null) {
            return $this->psr4Prefixes;
        }

        $composerJsonPath = $this->composerJsonPath ?? getcwd() . '/composer.json';
        if (! is_file($composerJsonPath)) {
            return $this->psr4Prefixes = [];
        }

        $composerJson = JsonFileSystem::readFilePath($composerJsonPath);
        $rootDirectory = dirname($composerJsonPath);

        $psr4Prefixes = [];
        foreach (['autoload', 'autoload-dev'] as $autoloadSection) {
            $psr4 = $composerJson[$autoloadSection]['psr-4'] ?? [];
            if (! is_array($psr4)) {
                continue;
            }

            foreach ($psr4 as $namespacePrefix => $directories) {
                foreach ((array) $directories as $directory) {
                    $psr4Prefixes[] = [
                        (string) $namespacePrefix,
                        $rootDirectory . '/' . rtrim((string) $directory, '/'),
                    ];
                }
            }
        }

        return $this->psr4Prefixes = $psr4Prefixes;
    }
}
