<?php

declare(strict_types=1);

namespace Rector\Console\Command;

use Composer\Semver\Semver;
use Entropy\Console\Contract\CommandInterface;
use Nette\Utils\Strings;
use Rector\Composer\InstalledPackageResolver;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Console\ExitCode;
use Rector\Console\Style\RectorStyle;
use Rector\Contract\Rector\RectorInterface;
use Rector\VersionBonding\Contract\ComposerPackageConstraintInterface;
use Rector\VersionBonding\ValueObject\ComposerBoundRuleConfiguration;
use ReflectionObject;

/**
 * @see \Rector\Tests\Console\Command\ComposerBasedCommandTest
 */
final readonly class ComposerBasedCommand implements CommandInterface
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private RectorStyle $rectorStyle,
        private InstalledPackageResolver $installedPackageResolver,
        private array $rectors
    ) {
    }

    public function getName(): string
    {
        return 'composer-based';
    }

    public function getDescription(): string
    {
        return 'Show loaded rules that are triggered by an installed composer package version';
    }

    /**
     * @api invoked via reflection by the Entropy console application
     *
     * @return ExitCode::*
     */
    public function run(): int
    {
        $tableRows = $this->createTableRows();
        $configurationTableRows = $this->createConfigurationTableRows();

        if ($tableRows === [] && $configurationTableRows === []) {
            $this->rectorStyle->warning('No composer package bound rule is loaded');

            return ExitCode::SUCCESS;
        }

        if ($tableRows !== []) {
            $this->rectorStyle->title('Composer package bound rules');
            $this->rectorStyle->table(['Rule', 'Package', 'Requires', 'Installed', 'Active'], $tableRows);
        }

        if ($configurationTableRows !== []) {
            $this->rectorStyle->title('Composer package bound rule configuration');
            $this->rectorStyle->table(
                ['Rule', 'Package', 'Requires', 'Installed', 'Active', 'Configuration'],
                $configurationTableRows
            );
        }

        $allTableRows = [...$tableRows, ...$configurationTableRows];
        $activeCount = count(array_filter($allTableRows, static fn (array $tableRow): bool => $tableRow[4] === 'yes'));

        $this->rectorStyle->note(
            sprintf('%d of %d composer package bound items are active', $activeCount, count($allTableRows))
        );

        return ExitCode::SUCCESS;
    }

    /**
     * @return array<array{string, string, string, string, string}>
     */
    private function createTableRows(): array
    {
        $tableRows = [];

        foreach ($this->rectors as $rector) {
            if (! $rector instanceof ComposerPackageConstraintInterface) {
                continue;
            }

            $composerPackageConstraint = $rector->provideComposerPackageConstraint();
            $composerPackageConstraints = is_array($composerPackageConstraint)
                ? $composerPackageConstraint
                : [$composerPackageConstraint];

            foreach ($composerPackageConstraints as $composerPackageConstraint) {
                $packageName = $composerPackageConstraint->getPackageName();
                $constraint = $composerPackageConstraint->getConstraint();

                $installedVersion = $this->installedPackageResolver->resolvePackageVersion($packageName);
                $isActive = $installedVersion !== null && Semver::satisfies($installedVersion, $constraint);

                $tableRows[] = [
                    $this->printShortClassName($rector::class),
                    $packageName,
                    $constraint,
                    $installedVersion ?? '-',
                    $isActive ? 'yes' : 'no',
                ];
            }
        }

        // sort by package name first, then by rule class
        usort(
            $tableRows,
            static fn (array $firstTableRow, array $secondTableRow): int => [$firstTableRow[1], $firstTableRow[0]] <=> [$secondTableRow[1], $secondTableRow[0]]
        );

        return $tableRows;
    }

    /**
     * @return array<array{string, string, string, string, string, string}>
     */
    private function createConfigurationTableRows(): array
    {
        $composerBoundRuleConfigurations = SimpleParameterProvider::provideArrayParameter(
            Option::COMPOSER_BOUND_RULE_CONFIGURATIONS
        );

        $tableRows = [];

        foreach ($composerBoundRuleConfigurations as $composerBoundRuleConfiguration) {
            if (! $composerBoundRuleConfiguration instanceof ComposerBoundRuleConfiguration) {
                continue;
            }

            $packageName = $composerBoundRuleConfiguration->getPackageName();
            $installedVersion = $this->installedPackageResolver->resolvePackageVersion($packageName);

            $tableRows[] = [
                $this->printShortClassName($composerBoundRuleConfiguration->getRectorClass()),
                $packageName,
                $composerBoundRuleConfiguration->getVersionConstraint(),
                $installedVersion ?? '-',
                $composerBoundRuleConfiguration->isActive() ? 'yes' : 'no',
                $this->printConfiguration($composerBoundRuleConfiguration->getConfiguration()),
            ];
        }

        return $tableRows;
    }

    /**
     * @param mixed[] $configuration
     */
    private function printConfiguration(array $configuration): string
    {
        $printedItems = [];

        foreach ($configuration as $key => $value) {
            $printedValue = $this->printConfigurationValue($value);

            $printedItems[] = is_string($key) ? $key . ': ' . $printedValue : $printedValue;
        }

        return implode(PHP_EOL, $printedItems);
    }

    private function printConfigurationValue(mixed $value): string
    {
        if (is_object($value)) {
            $printedPropertyValues = [];

            $reflectionObject = new ReflectionObject($value);
            foreach ($reflectionObject->getProperties() as $reflectionProperty) {
                // lazy-initialized property, e.g. PHPStan UnionType::$normalized
                if (! $reflectionProperty->isInitialized($value)) {
                    continue;
                }

                $printedPropertyValues[] = $this->printConfigurationValue($reflectionProperty->getValue($value));
            }

            return $this->printShortClassName($value::class) . '(' . implode(', ', $printedPropertyValues) . ')';
        }

        if (is_array($value)) {
            $printedItems = array_map(
                $this->printConfigurationValue(...),
                $value
            );

            return '[' . implode(', ', $printedItems) . ']';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }

    private function printShortClassName(string $className): string
    {
        return Strings::after($className, '\\', -1) ?? $className;
    }
}
