<?php

declare(strict_types=1);

namespace Rector\RuleDoc\CodeSample;

use Rector\RuleDoc\AbstractCodeSample;

/**
 * @api
 */
final class ComposerJsonAwareCodeSample extends AbstractCodeSample
{
    public function __construct(
        string $badCode,
        string $goodCode,
        private readonly string $composerJson
    ) {
        parent::__construct($badCode, $goodCode);
    }

    public function getComposerJson(): string
    {
        return $this->composerJson;
    }
}
