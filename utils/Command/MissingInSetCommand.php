<?php

declare(strict_types=1);

namespace Rector\Utils\Command;

use Nette\Loaders\RobotLoader;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MissingInSetCommand extends Command
{
    /**
     * @var array<string, string[]>
     */
    private const RULES_DIRECTORY_TO_SET_CONFIGS = [
        __DIR__ . '/../../rules/CodeQuality/Rector' => [__DIR__ . '/../../config/set/code-quality.php'],
        __DIR__ . '/../../rules/CodingStyle/Rector' => [__DIR__ . '/../../config/set/coding-style.php'],
        __DIR__ . '/../../rules/DeadCode/Rector' => [__DIR__ . '/../../config/set/dead-code.php'],
        __DIR__ . '/../../rules/EarlyReturn/Rector' => [__DIR__ . '/../../config/set/early-return.php'],
        __DIR__ . '/../../rules/Naming/Rector' => [__DIR__ . '/../../config/set/naming.php'],
        __DIR__ . '/../../rules/TypeDeclaration/Rector' => [
            __DIR__ . '/../../config/set/type-declaration.php',
            __DIR__ . '/../../config/set/type-declaration-strict.php',
        ],
    ];

    /**
     * @see https://regex101.com/r/HuWjgn/1
     * @var string
     */
    private const SHORT_CLASS_REGEX = '#(?<short_class_name>\w+)::class#m';

    public function __construct(
        private readonly SymfonyStyle $symfonyStyle
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('missing-in-set');
        $this->setDescription('[DEV] Show rules from specific category that are not part of the set');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (self::RULES_DIRECTORY_TO_SET_CONFIGS as $rulesDirectory => $setFiles) {
            $shortClassesInSetFile = $this->resolveShortClassesFromSetFiles($setFiles);
            $existingShortRectorClasses = $this->findShortRectorClasses($rulesDirectory);

            $shortRectorClassesNotInSetConfig = array_diff($existingShortRectorClasses, $shortClassesInSetFile);

            if ($shortRectorClassesNotInSetConfig === []) {
                continue;
            }

            $this->symfonyStyle->title('We could not find there rules in configs');

            foreach ($setFiles as $setFile) {
                $setRealpath = (string) realpath($setFile);
                $relativeFilePath = Strings::after($setRealpath, getcwd() . '/');

                $this->symfonyStyle->writeln(' * ' . $relativeFilePath);
            }

            $this->symfonyStyle->newLine(1);

            $this->symfonyStyle->listing($shortRectorClassesNotInSetConfig);
            $this->symfonyStyle->newLine(1);
        }

        return self::SUCCESS;
    }

    /**
     * @param string[] $setFiles
     * @return string[]
     */
    private function resolveShortClassesFromSetFiles(array $setFiles): array
    {
        $shortClasses = [];

        foreach ($setFiles as $setFile) {
            $setFileContents = FileSystem::read($setFile);
            $matches = Strings::matchAll($setFileContents, self::SHORT_CLASS_REGEX);

            foreach ($matches as $match) {
                $shortClasses[] = $match['short_class_name'];
            }
        }

        return $shortClasses;
    }

    /**
     * @return string[]
     */
    private function findShortRectorClasses(string $rulesDirectory): array
    {
        $robotLoader = new RobotLoader();
        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/rector-missing-in-set');
        $robotLoader->addDirectory($rulesDirectory);
        $robotLoader->rebuild();

        $existingRectorClasses = array_keys($robotLoader->getIndexedClasses());
        $existingRectorShortClasses = [];
        foreach ($existingRectorClasses as $existingRectorClass) {
            $existingRectorShortClasses[] = (string) Strings::after($existingRectorClass, '\\', -1);
        }

        return $existingRectorShortClasses;
    }
}
