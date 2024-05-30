<?php

declare(strict_types=1);

namespace Rector\Utils\Command;

use Nette\Utils\Strings;
use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector;
use Rector\Doctrine\CodeQuality\Rector\Class_\InitializeDefaultEntityCollectionRector;
use Rector\Doctrine\CodeQuality\Rector\Property\TypedPropertyFromDoctrineCollectionRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php81\Rector\ClassConst\FinalizePublicClassConstantRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\AddCoversClassAttributeRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\IncreaseDeclareStrictTypesRector;
use Rector\Utils\Enum\RectorDirectoryToSetFileMap;
use Rector\Utils\Finder\RectorClassFinder;
use Rector\Utils\Finder\SetRectorClassesResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class MissingInSetCommand extends Command
{
    /**
     * @var list<string>
     */
    private const SKIPPED_RULES = [
        ConfigurableRectorInterface::class,
        // optional
        IncreaseDeclareStrictTypesRector::class,
        CallableThisArrayToAnonymousFunctionRector::class,
        // changes behavior, should be applied on purpose regardless PHP 7.3 level
        JsonThrowOnErrorRector::class,
        // in confront with sub type safe belt detection on RemoveUseless*TagRector
        RemoveNullTagValueNodeRector::class,
        // personal preference, enable on purpose
        ArraySpreadInsteadOfArrayMergeRector::class,
        FinalizeClassesWithoutChildrenRector::class,
        // deprecated
        FinalizePublicClassConstantRector::class,

        // deprecated
        InitializeDefaultEntityCollectionRector::class,
        TypedPropertyFromDoctrineCollectionRector::class,

        // by guessing class detection
        AddCoversClassAttributeRector::class,
    ];

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

        foreach (RectorDirectoryToSetFileMap::provide() as $rulesDirectory => $setFile) {
            $classesInSetFile = SetRectorClassesResolver::resolve($setFile);
            $existingRectorClasses = RectorClassFinder::find([$rulesDirectory]);

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

            // probably in rector-preset?
            $classesInSetFile = SetRectorClassesResolver::resolve(__DIR__ . '/../../config/set/rector-preset.php');
            $rectorClassesNotInSetConfig = array_diff($rectorClassesNotInSetConfig, $classesInSetFile);
            if ($rectorClassesNotInSetConfig === []) {
                continue;
            }

            $hasError = true;
            $this->symfonyStyle->title('We could not find there rules in configs');

            $setRealpath = realpath($setFile);
            $relativeFilePath = Strings::after($setRealpath, getcwd() . '/');

            $this->symfonyStyle->writeln(' * ' . $relativeFilePath);
            $this->symfonyStyle->newLine(1);

            $this->symfonyStyle->listing($rectorClassesNotInSetConfig);
            $this->symfonyStyle->newLine(1);
        }

        if ($hasError) {
            return self::FAILURE;
        }

        $setCount = count(RectorDirectoryToSetFileMap::provide());

        $this->symfonyStyle->success(sprintf('All %d sets contains the rules from their category', $setCount));

        return self::SUCCESS;
    }
}
