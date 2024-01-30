<?php

namespace Rector\VersionBonding\Contract;

use Rector\VersionBonding\Composer\ComposerAnalyzer;

interface RequiredComposerCompatibilityInterface
{
    /**
     * Provide the rule's composer compatibility
     */
    public function meetsComposerRequirements(ComposerAnalyzer $analyzer): bool;
}
