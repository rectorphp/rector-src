<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\Contract\Rector\RectorInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Every Rector rule must be registered in a set or level config, otherwise it can
 * never run and becomes dead code. Registrations in sibling extension packages
 * (rector-doctrine, rector-symfony, ...) count too. Deprecated rules are skipped,
 * as they are intentionally unregistered.
 *
 * @implements Rule<InClassNode>
 * @see \Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\RegisterRectorRuleTest
 */
final class RegisterRectorRule implements Rule
{
    private const string ERROR_MESSAGE = 'Rector rule "%s" is not registered in any set or level config, so it can never run. Register it in a set/level, or deprecate it if it is no longer wanted.';

    /**
     * @var string[]
     */
    private readonly array $registrationDirectories;

    /**
     * @var array<string, true>|null
     */
    private ?array $registeredShortClassNames = null;

    /**
     * @param string[]|null $registrationDirectories
     */
    public function __construct(?array $registrationDirectories = null)
    {
        $root = __DIR__ . '/../../../../';

        $this->registrationDirectories = $registrationDirectories ?? array_merge(
            [$root . 'config', $root . 'src/Config'],
            // sibling extension packages register rules in their own config sets
            glob($root . 'vendor/rector/*/config') ?: []
        );
    }

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $node->getClassReflection();

        if ($classReflection->isAbstract()) {
            return [];
        }

        if (! $classReflection->implementsInterface(RectorInterface::class)) {
            return [];
        }

        // test doubles are wired directly in their test, not through a set
        if (str_starts_with($classReflection->getName(), 'Rector\\Tests\\')) {
            return [];
        }

        // intentionally unregistered
        if ($classReflection->implementsInterface(DeprecatedInterface::class)) {
            return [];
        }

        $className = $classReflection->getName();
        if ($this->isRegistered($className)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(self::ERROR_MESSAGE, $className))
                ->identifier('rector.registerRector')
                ->build(),
        ];
    }

    private function isRegistered(string $className): bool
    {
        $shortClassName = substr((string) strrchr($className, '\\'), 1);

        // short class names are unique repo-wide (enforced by the "no duplicate short class names" check)
        return isset($this->getRegisteredShortClassNames()[$shortClassName]);
    }

    /**
     * Collect every "SomeRector::class" short name referenced in the scanned config directories once.
     * The capture group stops at the preceding "\", so both "SomeRector::class" and a fully qualified
     * "\Some\Ns\SomeRector::class" yield the same short name.
     *
     * @return array<string, true>
     */
    private function getRegisteredShortClassNames(): array
    {
        if ($this->registeredShortClassNames !== null) {
            return $this->registeredShortClassNames;
        }

        $registeredShortClassNames = [];
        foreach ($this->registrationDirectories as $registrationDirectory) {
            if (! is_dir($registrationDirectory)) {
                continue;
            }

            $recursiveIterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($registrationDirectory, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            /** @var SplFileInfo $fileInfo */
            foreach ($recursiveIterator as $fileInfo) {
                if ($fileInfo->getExtension() !== 'php') {
                    continue;
                }

                $fileContents = file_get_contents($fileInfo->getRealPath());
                if ($fileContents === false) {
                    continue;
                }

                preg_match_all('#([A-Za-z_][A-Za-z0-9_]*)::class#', $fileContents, $matches);
                foreach ($matches[1] as $shortClassName) {
                    $registeredShortClassNames[$shortClassName] = true;
                }
            }
        }

        $this->registeredShortClassNames = $registeredShortClassNames;

        return $registeredShortClassNames;
    }
}
