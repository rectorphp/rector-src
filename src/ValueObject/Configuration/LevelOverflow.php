<?php

declare(strict_types=1);

namespace Rector\ValueObject\Configuration;

final readonly class LevelOverflow
{
    public function __construct(
        private string $configurationName,
        private int $level,
        private int $ruleCount,
        private string $suggestedRuleset,
        private string $suggestedSetListConstant
    ) {
    }

    public function getConfigurationName(): string
    {
        return $this->configurationName;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getRuleCount(): int
    {
        return $this->ruleCount;
    }

    public function getSuggestedRuleset(): string
    {
        return $this->suggestedRuleset;
    }

    public function getSuggestedSetListConstant(): string
    {
        return $this->suggestedSetListConstant;
    }
}
