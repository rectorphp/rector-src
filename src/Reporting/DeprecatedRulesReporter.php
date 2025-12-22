<?php

declare(strict_types=1);

namespace Rector\Reporting;

use Rector\Configuration\Deprecation\Contract\DeprecatedInterface;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Contract\Rector\RectorInterface;
use Rector\PhpParser\Enum\NodeGroup;
<<<<<<< HEAD
use Rector\PhpParserNode\FileNode;
=======
<<<<<<< HEAD
>>>>>>> 6bcee47e76 (warn about deprecated type)
use ReflectionMethod;
=======
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PhpParser\Node\FileNode;
>>>>>>> 77056cdc84 (warn about deprecated type)
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class DeprecatedRulesReporter
{
    /**
     * @param RectorInterface[] $rectors
     */
    public function __construct(
        private SymfonyStyle $symfonyStyle,
        private array $rectors
    ) {
    }

    public function reportDeprecatedRules(): void
    {
        /** @var string[] $registeredRectorRules */
        $registeredRectorRules = SimpleParameterProvider::provideArrayParameter(Option::REGISTERED_RECTOR_RULES);

        foreach ($registeredRectorRules as $registeredRectorRule) {
            if (! is_a($registeredRectorRule, DeprecatedInterface::class, true)) {
                continue;
            }

            $this->symfonyStyle->warning(
                sprintf(
                    'Registered rule "%s" is deprecated and will be removed. Upgrade your config to use another rule or remove it',
                    $registeredRectorRule
                )
            );
        }
    }

    public function reportDeprecatedSkippedRules(): void
    {
        /** @var string[] $skippedRectorRules */
        $skippedRectorRules = SimpleParameterProvider::provideArrayParameter(Option::SKIPPED_RECTOR_RULES);

        foreach ($skippedRectorRules as $skippedRectorRule) {
            if (! is_a($skippedRectorRule, DeprecatedInterface::class, true)) {
                continue;
            }

            $this->symfonyStyle->warning(sprintf('Skipped rule "%s" is deprecated', $skippedRectorRule));
        }
    }

    public function reportDeprecatedRectorUnsupportedMethods(): void
    {
        // to be added in related PR
        if (! class_exists(FileNode::class)) {
            return;
        }

        foreach ($this->rectors as $rector) {
            $beforeTraverseMethodReflection = new ReflectionMethod($rector, 'beforeTraverse');
            if ($beforeTraverseMethodReflection->getDeclaringClass()->getName() === $rector::class) {
                $this->symfonyStyle->warning(sprintf(
                    'Rector rule "%s" uses deprecated "beforeTraverse" method. It should not be used, as will be marked as final. Not part of RectorInterface contract. Use "%s" to hook into file-level changes instead.',
                    $rector::class,
                    FileNode::class
                ));
            }
        }
    }

    public function reportDeprecatedNodeTypes(): void
    {
        // helper property to avoid reporting multiple times
        static $reportedClasses = [];

        foreach ($this->rectors as $rector) {
            if (in_array(FileWithoutNamespace::class, $rector->getNodeTypes(), true)) {
                $this->reportDeprecatedFileWithoutNamespace($rector);
                continue;
            }

            if (! in_array(StmtsAwareInterface::class, $rector->getNodeTypes())) {
                continue;
            }

            // already reported, skip
            if (in_array($rector::class, $reportedClasses, true)) {
                continue;
            }

            $reportedClasses[] = $rector::class;

            $this->symfonyStyle->warning(sprintf(
                'Rector rule "%s" uses StmtsAwareInterface that is now deprecated.%sUse "%s::%s" instead.%sSee %s for more',
                $rector::class,
                PHP_EOL,
                NodeGroup::class,
                'STMTS_AWARE',
                PHP_EOL . PHP_EOL,
                'https://github.com/rectorphp/rector-src/pull/7679'
            ));
        }
    }

    private function reportDeprecatedFileWithoutNamespace(RectorInterface $rector): void
    {
        $this->symfonyStyle->warning(sprintf(
            'Node type "%s" is deprecated and will be removed. Use "%s" in the "%s" rule instead instead.%sSee %s for upgrade path',
            FileWithoutNamespace::class,
            FileNode::class,
            $rector::class,
            PHP_EOL . PHP_EOL,
            'https://github.com/rectorphp/rector-src/blob/main/UPGRADING.md'
        ));
    }
}
