<?php

declare(strict_types=1);

namespace Rector\Core\NonPhpFile\Rector;

use Nette\Utils\Strings;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\NonPhpRectorInterface;
use Rector\PostRector\Contract\Rector\ComplementaryRectorInterface;
use Symplify\RuleDocGenerator\Contract\ConfigurableRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RenameClassNonPhpRector implements NonPhpRectorInterface, ConfigurableRuleInterface, ConfigurableRectorInterface, ComplementaryRectorInterface
{
    /**
     * @see https://regex101.com/r/HKUFJD/7
     * for "?<!" @see https://stackoverflow.com/a/3735908/1348344
     * @var string
     */
    private const STANDALONE_CLASS_PREFIX_REGEX = '#((?<!(\\\\|"|\>|\.|\'))|(?<extra_space>\s+\\\\))';

    /**
     * @see https://regex101.com/r/HKUFJD/5
     * @see https://stackoverflow.com/a/3926546/1348344
     * @var string
     */
    private const STANDALONE_CLASS_SUFFIX_REGEX = '(?=::)#';

    /**
     * @var array<string, string>
     */
    private array $renameClasses = [];

    public function __construct(
        private readonly RenamedClassesDataCollector $renamedClassesDataCollector,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change class names and just renamed classes in non-PHP files, NEON, YAML, TWIG, LATTE, blade etc. mostly with regular expressions',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
services:
    - SomeOldClass
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
services:
    - SomeNewClass
CODE_SAMPLE
                    ,
                    [
                        'SomeOldClass' => 'SomeNewClass',
                    ]
                ),
            ]
        );
    }

    public function refactorFileContent(string $fileContent): string
    {
        $classRenames = $this->getRenameClasses();
        return $this->renameClasses($fileContent, $classRenames);
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $renameClasses = $configuration;

        Assert::allString(array_keys($renameClasses));
        Assert::allString($renameClasses);

        $this->renameClasses = $renameClasses;
    }

    /**
     * @param array<string, string> $classRenames
     */
    private function renameClasses(string $newContent, array $classRenames): string
    {
        $classRenames = $this->addDoubleSlashed($classRenames);

        foreach ($classRenames as $oldClass => $newClass) {
            // the old class is without slashes, it can make mess as similar to a word in the text, so we have to be more strict about it
            $oldClassRegex = $this->createOldClassRegex($oldClass);
            $newContent = Strings::replace(
                $newContent,
                $oldClassRegex,
                static fn (array $match): string => ($match['extra_space'] ?? '') . $newClass
            );
        }

        return $newContent;
    }

    /**
     * Process with double quotes too, e.g. in twig
     *
     * @param array<string, string> $classRenames
     * @return array<string, string>
     */
    private function addDoubleSlashed(array $classRenames): array
    {
        foreach ($classRenames as $oldClass => $newClass) {
            // to prevent no slash override
            if (! \str_contains($oldClass, '\\')) {
                continue;
            }

            $doubleSlashOldClass = str_replace('\\', '\\\\', $oldClass);
            $doubleSlashNewClass = str_replace('\\', '\\\\', $newClass);

            $classRenames[$doubleSlashOldClass] = $doubleSlashNewClass;
        }

        return $classRenames;
    }

    /**
     * @return array<string, string>
     */
    private function getRenameClasses(): array
    {
        /** @var array<string, string> $renameClasses */
        $renameClasses = [...$this->renameClasses, ...$this->renamedClassesDataCollector->getOldToNewClasses()];
        return $renameClasses;
    }

    private function createOldClassRegex(string $oldClass): string
    {
        if (! \str_contains($oldClass, '\\')) {
            return self::STANDALONE_CLASS_PREFIX_REGEX
                . preg_quote($oldClass, '#')
                . self::STANDALONE_CLASS_SUFFIX_REGEX;
        }

        return '#' . preg_quote($oldClass, '#') . '#';
    }
}
