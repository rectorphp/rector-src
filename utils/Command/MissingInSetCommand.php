<?php

declare(strict_types=1);

namespace Rector\Utils\Command;

use Nette\Utils\Strings;
use Rector\CodingStyle\Rector\ClassMethod\DataProviderArrayItemsNewlinedRector;
use Rector\CodingStyle\Rector\Property\NullifyUnionNullableRector;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector;
use Rector\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\TypeDeclaration\Rector\Ternary\FlipNegatedTernaryInstanceofRector;
use Rector\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector;
use Rector\Utils\Finder\RectorClassFinder;
use Rector\Utils\Finder\SetRectorClassesResolver;
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
        __DIR__ . '/../../rules/Privatization/Rector' => __DIR__ . '/../../config/set/privatization.php',
        __DIR__ . '/../../rules/Php52/Rector' => __DIR__ . '/../../config/set/php52.php',
        __DIR__ . '/../../rules/Php53/Rector' => __DIR__ . '/../../config/set/php53.php',
        __DIR__ . '/../../rules/Php54/Rector' => __DIR__ . '/../../config/set/php54.php',
        __DIR__ . '/../../rules/Php55/Rector' => __DIR__ . '/../../config/set/php55.php',
        __DIR__ . '/../../rules/Php56/Rector' => __DIR__ . '/../../config/set/php56.php',
        __DIR__ . '/../../rules/Php70/Rector' => __DIR__ . '/../../config/set/php70.php',
        __DIR__ . '/../../rules/Php71/Rector' => __DIR__ . '/../../config/set/php71.php',
        __DIR__ . '/../../rules/Php72/Rector' => __DIR__ . '/../../config/set/php72.php',
        __DIR__ . '/../../rules/Php73/Rector' => __DIR__ . '/../../config/set/php73.php',
        __DIR__ . '/../../rules/Php74/Rector' => __DIR__ . '/../../config/set/php74.php',
        __DIR__ . '/../../rules/Php80/Rector' => __DIR__ . '/../../config/set/php80.php',
        __DIR__ . '/../../rules/Php81/Rector' => __DIR__ . '/../../config/set/php81.php',
        __DIR__ . '/../../rules/Php82/Rector' => __DIR__ . '/../../config/set/php82.php',
        __DIR__ . '/../../rules/Strict/Rector' => __DIR__ . '/../../config/set/strict-booleans.php',
        // rector-downgrade
        __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp71' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php71.php',
        __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp72' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php72.php',
        __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp73' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php73.php',
        __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp74' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php74.php',
        __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp80' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php80.php',
        __DIR__ . '/../../vendor/rector/rector-downgrade-php/rules/DowngradePhp81' => __DIR__ . '/../../vendor/rector/rector-downgrade-php/config/set/downgrade-php81.php',
    ];

    /**
     * @var list<string>
     */
    private const SKIPPED_RULES = [
        ConfigurableRectorInterface::class,
        RemoveJustPropertyFetchRector::class,
        NullifyUnionNullableRector::class,
        DeclareStrictTypesRector::class,
        // optional
        DataProviderArrayItemsNewlinedRector::class,
        FlipNegatedTernaryInstanceofRector::class,
        BinaryOpNullableToInstanceofRector::class,
        WhileNullableToInstanceofRector::class,
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

        foreach (self::RULES_DIRECTORY_TO_SET_CONFIG as $rulesDirectory => $setFile) {
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

        $this->symfonyStyle->success(
            sprintf('All %d sets contains the rules from their category', count(self::RULES_DIRECTORY_TO_SET_CONFIG))
        );

        return self::SUCCESS;
    }
}
