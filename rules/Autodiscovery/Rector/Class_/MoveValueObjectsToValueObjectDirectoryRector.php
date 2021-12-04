<?php

declare(strict_types=1);

namespace Rector\Autodiscovery\Rector\Class_;

use Controller;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Autodiscovery\Analyzer\ValueObjectClassAnalyzer;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithNodes;
use Rector\FileSystemRector\ValueObjectFactory\AddedFileWithNodesFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Inspiration @see https://github.com/rectorphp/rector/pull/1865/files#diff-0d18e660cdb626958662641b491623f8
 * @wip
 *
 * @see \Rector\Tests\Autodiscovery\Rector\Class_\MoveValueObjectsToValueObjectDirectoryRector\MoveValueObjectsToValueObjectDirectoryRectorTest
 */
final class MoveValueObjectsToValueObjectDirectoryRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const TYPES = 'types';

    /**
     * @var string
     */
    public const SUFFIXES = 'suffixes';

    /**
     * @api
     * @var string
     */
    public const ENABLE_VALUE_OBJECT_GUESSING = 'enable_value_object_guessing';

    /**
     * @var string[]|class-string<Controller>[]
     */
    private const COMMON_SERVICE_SUFFIXES = [
        'Repository', 'Command', 'Mapper', 'Controller', 'Presenter', 'Factory', 'Test', 'TestCase', 'Service',
    ];

    private bool $enableValueObjectGuessing = true;

    /**
     * @var string[]
     */
    private array $types = [];

    /**
     * @var string[]
     */
    private array $suffixes = [];

    public function __construct(
        private readonly AddedFileWithNodesFactory $addedFileWithNodesFactory,
        private readonly ValueObjectClassAnalyzer $valueObjectClassAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Move value object to ValueObject namespace/directory', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
// app/Exception/Name.php
class Name
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
// app/ValueObject/Name.php
class Name
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
CODE_SAMPLE
                ,
                [
                    self::TYPES => ['ValueObjectInterfaceClassName'],
                    self::SUFFIXES => ['Search'],
                    self::ENABLE_VALUE_OBJECT_GUESSING => true,
                ]
            ),
        ]);
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
        if (! $this->isValueObjectMatch($node)) {
            return null;
        }

        $smartFileInfo = $this->file->getSmartFileInfo();

        $addedFileWithNodes = $this->addedFileWithNodesFactory->createWithDesiredGroup(
            $smartFileInfo,
            $this->file,
            'ValueObject'
        );

        if (! $addedFileWithNodes instanceof AddedFileWithNodes) {
            return null;
        }

        $this->removedAndAddedFilesCollector->removeFile($smartFileInfo);
        $this->removedAndAddedFilesCollector->addAddedFile($addedFileWithNodes);

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $types = $configuration[self::TYPES] ?? [];
        Assert::isArray($types);
        Assert::allString($types);
        $this->types = $types;

        $suffixes = $configuration[self::SUFFIXES] ?? [];
        Assert::isArray($suffixes);
        Assert::allString($suffixes);
        $this->suffixes = $suffixes;

        $enableValueObjectGuessing = $configuration[self::ENABLE_VALUE_OBJECT_GUESSING] ?? false;
        Assert::boolean($enableValueObjectGuessing);
        $this->enableValueObjectGuessing = $enableValueObjectGuessing;
    }

    private function isValueObjectMatch(Class_ $class): bool
    {
        if ($this->isSuffixMatch($class)) {
            return true;
        }

        $className = $this->getName($class);
        if ($className === null) {
            return false;
        }

        $classObjectType = new ObjectType($className);

        foreach ($this->types as $type) {
            $desiredObjectType = new ObjectType($type);
            if ($desiredObjectType->isSuperTypeOf($classObjectType)->yes()) {
                return true;
            }
        }

        if ($this->isKnownServiceType($className)) {
            return false;
        }

        if (! $this->enableValueObjectGuessing) {
            return false;
        }

        return $this->valueObjectClassAnalyzer->isValueObjectClass($class);
    }

    private function isSuffixMatch(Class_ $class): bool
    {
        $className = $this->getName($class);
        if (! is_string($className)) {
            return false;
        }

        foreach ($this->suffixes as $suffix) {
            if (\str_ends_with($className, $suffix)) {
                return true;
            }
        }

        return false;
    }

    private function isKnownServiceType(string $className): bool
    {
        foreach (self::COMMON_SERVICE_SUFFIXES as $commonServiceSuffix) {
            if (\str_ends_with($className, $commonServiceSuffix)) {
                return true;
            }
        }

        return false;
    }
}
