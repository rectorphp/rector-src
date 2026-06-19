<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\VersionBonding\Contract\RelatedPolyfillInterface;

/**
 * Every Rector rule that implements RelatedPolyfillInterface must be registered
 * in config/set/php-polyfills.php, so the polyfill set stays complete.
 *
 * @implements Rule<InClassNode>
 * @see \Rector\Utils\PHPStan\Tests\Rule\RegisterRelatedPolyfillRectorRule\RegisterRelatedPolyfillRectorRuleTest
 */
final readonly class RegisterRelatedPolyfillRectorRule implements Rule
{
    private const string ERROR_MESSAGE = 'Class "%s" implements RelatedPolyfillInterface, but is not registered in config/set/php-polyfills.php. Register it there.';

    public function __construct(
        private string $polyfillConfigFilePath
    ) {
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
        if (! $classReflection->implementsInterface(RelatedPolyfillInterface::class)) {
            return [];
        }

        $className = $classReflection->getName();
        if ($this->isRegistered($className)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(self::ERROR_MESSAGE, $className))
                ->identifier('rector.registerRelatedPolyfill')
                ->build(),
        ];
    }

    private function isRegistered(string $className): bool
    {
        $configFileContents = file_get_contents($this->polyfillConfigFilePath);
        if ($configFileContents === false) {
            return false;
        }

        $shortClassName = substr((string) strrchr($className, '\\'), 1);

        return str_contains($configFileContents, $shortClassName . '::class');
    }
}
