<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\Enum_;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use Rector\Rector\AbstractRector;
use Rector\Renaming\Collector\RenamedEnumCaseCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\EnumCaseToPascalCaseRectorTest
 */
final class EnumCaseToPascalCaseRector extends AbstractRector
{
    public function __construct(
        private readonly RenamedEnumCaseCollector $renamedEnumCaseCollector,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert enum cases to PascalCase and update their usages',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
                    enum Status
                    {
                        case PENDING;
                        case published;
                        case IN_REVIEW;
                        case waiting_for_approval;
                    }
                    CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
                    enum Status
                    {
                        case Pending;
                        case Published;
                        case InReview;
                        case WaitingForApproval;
                    }
                    CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Enum_::class];
    }

    /**
     * @param Enum_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $enumName = $this->getName($node);
        if ($enumName === null) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof EnumCase) {
                continue;
            }

            $currentName = $stmt->name->toString();
            $pascalCaseName = $this->convertToPascalCase($currentName);

            if ($currentName === $pascalCaseName) {
                continue;
            }

            $stmt->name = new Identifier($pascalCaseName);
            $hasChanged = true;
        }

        if ($hasChanged) {
            $this->renamedEnumCaseCollector->add($enumName);
            return $node;
        }

        return null;
    }

    private function convertToPascalCase(string $name): string
    {
        $parts = explode('_', strtolower($name));
        return implode('', array_map(ucfirst(...), $parts));
    }
}
