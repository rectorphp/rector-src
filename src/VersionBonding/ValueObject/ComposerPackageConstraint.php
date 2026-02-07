<?php

declare(strict_types=1);

namespace Rector\VersionBonding\ValueObject;

/**
 * @api used by extensions
 */
final readonly class ComposerPackageConstraint
{
    public function __construct(
        private string $packageName,
        private string $constraint,
    ) {
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getConstraint(): string
    {
        return $this->constraint;
    }
}
