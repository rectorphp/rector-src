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
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Contract\Rector\RectorInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Every non-configurable Rector rule must be registered in a set or level config,
 * otherwise it can never run and becomes dead code. Configurable rules are skipped,
 * as they are shipped for downstream configs to register. Deprecated rules are
 * skipped too, as they are intentionally unregistered.
 *
 * @implements Rule<InClassNode>
 * @see \Rector\Utils\PHPStan\Tests\Rule\RegisterRectorRule\RegisterRectorRuleTest
 */
final class RegisterRectorRule implements Rule
{
    private const string ERROR_MESSAGE = 'Rector rule "%s" is not registered in any set or level config, so it can never run. Register it in a set/level, or implement ConfigurableRectorInterface if it is meant for downstream configs only.';

    /**
     * @var string[]
     */
    private array $registrationDirectories;

    private ?string $registrationContents = null;

    /**
     * @param string[]|null $registrationDirectories
     */
    public function __construct(?array $registrationDirectories = null)
    {
        $this->registrationDirectories = $registrationDirectories ?? [
            __DIR__ . '/../../../../config',
            __DIR__ . '/../../../../src/Config',
        ];
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

        // shipped for downstream configs to register
        if ($classReflection->implementsInterface(ConfigurableRectorInterface::class)) {
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

        // match "ShortName::class", also as a fully qualified "\Some\Ns\ShortName::class",
        // but not a longer class name ending with it, e.g. "SubShortName::class"
        $pattern = '#(?<![a-zA-Z0-9_])' . preg_quote($shortClassName, '#') . '::class#';

        return preg_match($pattern, $this->getRegistrationContents()) === 1;
    }

    private function getRegistrationContents(): string
    {
        if ($this->registrationContents !== null) {
            return $this->registrationContents;
        }

        $contents = '';
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

                $contents .= $fileContents;
            }
        }

        $this->registrationContents = $contents;

        return $contents;
    }
}
