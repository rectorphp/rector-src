<?php

declare(strict_types=1);

namespace Rector\Configuration;

use Composer\Autoload\ClassLoader;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Contract\Rector\RectorInterface;
use Rector\Exception\Configuration\RectorRuleNameAmbiguousException;
use Rector\Exception\Configuration\RectorRuleNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @see \Rector\Tests\Configuration\OnlyRuleResolverTest
 */
final readonly class OnlyRuleResolver
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private array $rectors,
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function resolve(string $rule): string
    {
        // fix wrongly double escaped backslashes
        $rule = str_replace('\\\\', '\\', $rule);

        // remove single quotes appearing when single-quoting arguments on windows
        if (str_starts_with($rule, "'") && str_ends_with($rule, "'")) {
            $rule = substr($rule, 1, -1);
        }

        $rule = ltrim($rule, '\\');

        foreach ($this->rectors as $rector) {
            if ($rector::class === $rule) {
                return $rule;
            }
        }

        // allow short rule names if there are not duplicates
        $matching = [];
        foreach ($this->rectors as $rector) {
            if (str_ends_with($rector::class, '\\' . $rule)) {
                $matching[] = $rector::class;
            }
        }

        $matching = array_unique($matching);
        if (count($matching) === 1) {
            return $matching[0];
        }

        if (count($matching) > 1) {
            sort($matching);
            $message = sprintf(
                'Short rule name "%s" is ambiguous. Specify the full rule name:' . PHP_EOL
                    . '- ' . implode(PHP_EOL . '- ', $matching),
                $rule
            );
            throw new RectorRuleNameAmbiguousException($message);
        }

        // the rule class exists, it is just missing in the config
        $unregisteredRuleClasses = $this->matchUnregisteredRuleClasses($rule);
        if ($unregisteredRuleClasses !== []) {
            throw new RectorRuleNotFoundException($this->createUnregisteredMessage($rule, $unregisteredRuleClasses));
        }

        if (! str_contains($rule, '\\')) {
            // the shell has eaten unescaped backslashes, e.g. --only=\Rector\Some\Rule
            $flattenMatching = [];
            foreach ($this->rectors as $rector) {
                if (str_replace('\\', '', $rector::class) === $rule) {
                    $flattenMatching[] = $rector::class;
                }
            }

            $flattenMatching = array_unique($flattenMatching);
            if (count($flattenMatching) === 1) {
                return $flattenMatching[0];
            }

            $message = sprintf(
                'Rule "%s" was not found.%sThe rule has no namespace. Make sure to escape the backslashes, and add quotes around the rule name: --only="My\\Rector\\Rule"',
                $rule,
                PHP_EOL
            );
        } else {
            $message = sprintf(
                'Rule "%s" was not found.%sMake sure it is registered in your config or in one of the sets',
                $rule,
                PHP_EOL
            );
        }

        throw new RectorRuleNotFoundException($message);
    }

    /**
     * Rule classes that exist in the autoloaded code, but are not registered in the config
     *
     * @return string[]
     */
    private function matchUnregisteredRuleClasses(string $rule): array
    {
        if (str_contains($rule, '\\')) {
            return $this->isRectorRuleClass($rule) ? [$rule] : [];
        }

        $ruleClasses = [];

        foreach ($this->resolvePsr4Prefixes() as $namespacePrefix => $directories) {
            foreach ($directories as $directory) {
                if (! is_dir($directory)) {
                    continue;
                }

                $finder = Finder::create()
                    ->files()
                    ->in($directory)
                    ->name($rule . '.php');

                foreach ($finder as $fileInfo) {
                    $ruleClass = $this->createClassName($namespacePrefix, $directory, $fileInfo);
                    if ($this->isRectorRuleClass($ruleClass)) {
                        $ruleClasses[] = $ruleClass;
                    }
                }
            }
        }

        $ruleClasses = array_unique($ruleClasses);
        sort($ruleClasses);

        return $ruleClasses;
    }

    private function isRectorRuleClass(string $className): bool
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        if ($classReflection->isAbstract()) {
            return false;
        }

        return $classReflection->implementsInterface(RectorInterface::class);
    }

    /**
     * Only Rector rule namespaces are worth scanning, as rules always live there
     *
     * @return array<string, string[]>
     */
    private function resolvePsr4Prefixes(): array
    {
        $psr4Prefixes = [];

        foreach (spl_autoload_functions() as $autoloadFunction) {
            if (! is_array($autoloadFunction)) {
                continue;
            }

            $classLoader = $autoloadFunction[0];
            if (! $classLoader instanceof ClassLoader) {
                continue;
            }

            foreach ($classLoader->getPrefixesPsr4() as $namespacePrefix => $directories) {
                if (! str_contains($namespacePrefix, 'Rector')) {
                    continue;
                }

                $psr4Prefixes[$namespacePrefix] = array_merge(
                    $psr4Prefixes[$namespacePrefix] ?? [],
                    array_values($directories)
                );
            }
        }

        return $psr4Prefixes;
    }

    private function createClassName(string $namespacePrefix, string $directory, SplFileInfo $fileInfo): string
    {
        $relativeDirectory = trim(str_replace($directory, '', $fileInfo->getPath()), '/');
        $namespace = $namespacePrefix . str_replace('/', '\\', $relativeDirectory);

        return rtrim($namespace, '\\') . '\\' . $fileInfo->getFilenameWithoutExtension();
    }

    /**
     * @param string[] $unregisteredRuleClasses
     */
    private function createUnregisteredMessage(string $rule, array $unregisteredRuleClasses): string
    {
        if (count($unregisteredRuleClasses) === 1) {
            $unregisteredRuleClass = $unregisteredRuleClasses[0];
            $shortRuleClass = substr((string) strrchr($unregisteredRuleClass, '\\'), 1);

            return sprintf(
                'Rule "%s" exists, but is not registered in your Rector config.%sRegister it in your rector.php:'
                    . PHP_EOL . PHP_EOL . '    ->withRules([%s::class])',
                $unregisteredRuleClass,
                PHP_EOL,
                $shortRuleClass
            );
        }

        return sprintf(
            'Rule "%s" exists in these classes, but none of them is registered in your Rector config:' . PHP_EOL
                . '- ' . implode(PHP_EOL . '- ', $unregisteredRuleClasses) . PHP_EOL
                . 'Register one of them in your rector.php',
            $rule
        );
    }
}
