<?php

declare(strict_types=1);

namespace Rector\Utils\Command;

use Nette\Loaders\RobotLoader;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector;
use Rector\CodingStyle\Rector\ClassMethod\DataProviderArrayItemsNewlinedRector;
use Rector\CodingStyle\Rector\Property\NullifyUnionNullableRector;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\DeprecatedRectorInterface;
use Rector\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector;
use Rector\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector;
use Rector\TypeDeclaration\Rector\ClassMethod\FalseReturnClassMethodToNullableRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\TypeDeclaration\Rector\Ternary\FlipNegatedTernaryInstanceofRector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MissingInSetCommand extends Command
{
    /**
     * @var array<string, string>
     */
    private const RULES_DIRECTORY_TO_SET_CONFIG = [
        __DIR__ . '/../../rules/CodeQuality/Rector' => __DIR__ . '/../../config/set/code-quality.php',
        __DIR__ . '/../../rules/CodingStyle/Rector' => __DIR__ . '/../../config/set/coding-style.php',
        __DIR__ . '/../../rules/DeadCode/Rector' => __DIR__ . '/../../config/set/dead-code.php',
        __DIR__ . '/../../rules/EarlyReturn/Rector' => __DIR__ . '/../../config/set/early-return.php',
        __DIR__ . '/../../rules/Naming/Rector' => __DIR__ . '/../../config/set/naming.php',
        __DIR__ . '/../../rules/TypeDeclaration/Rector' => __DIR__ . '/../../config/set/type-declaration.php',
    ];

    /**
     * @var list<string>
     */
    private const SKIPPED_RULES = [
        ConfigurableRectorInterface::class,
        DeprecatedRectorInterface::class,
        ConvertStaticPrivateConstantToSelfRector::class,
        RemoveJustPropertyFetchRector::class,
        FalseReturnClassMethodToNullableRector::class,
        NullifyUnionNullableRector::class,
        DeclareStrictTypesRector::class,
        // optional
        DataProviderArrayItemsNewlinedRector::class,
        FlipNegatedTernaryInstanceofRector::class,
        BinaryOpNullableToInstanceofRector::class,
    ];

    /**
     * @see https://regex101.com/r/HtsmKC/1
     * @var string
     */
    private const RECTOR_CLASS_REGEX = '#use (?<class_name>[\\\\\w]+Rector)#m';

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
        $hasError = false;

        foreach (self::RULES_DIRECTORY_TO_SET_CONFIG as $rulesDirectory => $setFile) {
            $classesInSetFile = $this->resolveClassesFromSetFiles($setFile);
            $existingRectorClasses = $this->findRectorClasses($rulesDirectory);

            $rectorClassesNotInSetConfig = array_diff($existingRectorClasses, $classesInSetFile);

            // remove deprecated and configurable rules
            $rectorClassesNotInSetConfig = array_filter(
                $rectorClassesNotInSetConfig,
                static function (string $rectorClass): bool {
                    foreach (self::SKIPPED_RULES as $rule) {
                        if (is_a($rectorClass, $rule, true)) {
                            return false;
                        }
                    }

                    return true;
                }
            );

            if ($rectorClassesNotInSetConfig === []) {
                continue;
            }

            $hasError = true;
            $this->symfonyStyle->title('We could not find there rules in configs');

            $setRealpath = (string) realpath($setFile);
            $relativeFilePath = Strings::after($setRealpath, getcwd() . '/');

            $this->symfonyStyle->writeln(' * ' . $relativeFilePath);
            $this->symfonyStyle->newLine(1);

            $this->symfonyStyle->listing($rectorClassesNotInSetConfig);
            $this->symfonyStyle->newLine(1);
        }

        if ($hasError) {
            return self::FAILURE;
        }

        $this->symfonyStyle->success('All sets contains the rules from their category');

        return self::SUCCESS;
    }

    /**
     * @return string[]
     */
    private function resolveClassesFromSetFiles(string $setFile): array
    {
        $rectorClasses = [];

        $setFileContents = FileSystem::read($setFile);
        $matches = Strings::matchAll($setFileContents, self::RECTOR_CLASS_REGEX);

        foreach ($matches as $match) {
            $rectorClasses[] = $match['class_name'];
        }

        return $rectorClasses;
    }

    /**
     * @return string[]
     */
    private function findRectorClasses(string $rulesDirectory): array
    {
        $robotLoader = new RobotLoader();
        $robotLoader->setTempDirectory(sys_get_temp_dir() . '/rector-missing-in-set');
        $robotLoader->addDirectory($rulesDirectory);
        $robotLoader->rebuild();

        return array_keys($robotLoader->getIndexedClasses());
    }
}
