<?php

declare(strict_types=1);

namespace Rector\DeadCode;

use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Php\PhpVersionProvider;
use Rector\DeadCode\Contract\ConditionInterface;
use Rector\DeadCode\ValueObject\BinaryToVersionCompareCondition;
use Rector\DeadCode\ValueObject\VersionCompareCondition;

final class ConditionEvaluator
{
    public function __construct(
        private readonly PhpVersionProvider $phpVersionProvider
    ) {
    }

    public function evaluate(ConditionInterface $condition): bool|int|null
    {
        if ($condition instanceof VersionCompareCondition) {
            return $this->evaluateVersionCompareCondition($condition);
        }

        if ($condition instanceof BinaryToVersionCompareCondition) {
            return $this->isEvaluedAsTrue($condition);
        }

        return null;
    }

    private function evaluateVersionCompareCondition(
        VersionCompareCondition $versionCompareCondition
    ): bool | int | null {
        $compareSign = $versionCompareCondition->getCompareSign();
        if ($compareSign !== null) {
            if ($compareSign === '<' && $this->phpVersionProvider->provide() < $versionCompareCondition->getSecondVersion()) {
                return null;
            }

            return version_compare(
                (string) $versionCompareCondition->getFirstVersion(),
                (string) $versionCompareCondition->getSecondVersion(),
                $compareSign
            );
        }

        return version_compare(
            (string) $versionCompareCondition->getFirstVersion(),
            (string) $versionCompareCondition->getSecondVersion()
        );
    }

    private function isEvaluedAsTrue(BinaryToVersionCompareCondition $binaryToVersionCompareCondition): bool
    {
        $versionCompareResult = $this->evaluateVersionCompareCondition(
            $binaryToVersionCompareCondition->getVersionCompareCondition()
        );

        if ($binaryToVersionCompareCondition->getBinaryClass() === Identical::class) {
            return $binaryToVersionCompareCondition->getExpectedValue() === $versionCompareResult;
        }

        if ($binaryToVersionCompareCondition->getBinaryClass() === NotIdentical::class) {
            return $binaryToVersionCompareCondition->getExpectedValue() !== $versionCompareResult;
        }

        if ($binaryToVersionCompareCondition->getBinaryClass() === Equal::class) {
            // weak comparison on purpose
            return $binaryToVersionCompareCondition->getExpectedValue() === $versionCompareResult;
        }

        if ($binaryToVersionCompareCondition->getBinaryClass() === NotEqual::class) {
            // weak comparison on purpose
            return $binaryToVersionCompareCondition->getExpectedValue() !== $versionCompareResult;
        }

        throw new ShouldNotHappenException();
    }
}
